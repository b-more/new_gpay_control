<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmationMail;
use App\Models\Business;
use App\Models\CurrentBalance;
use App\Models\Deposit;
use App\Models\FrontEndUrl;
use App\Models\Payment;
use App\Models\PaymentCommission;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_refunds = Refund::where('status', 2)->get();
        return view('refunds.index', compact('all_refunds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Payment::where('id', $request->id)->where('is_refunded', 0)->where('is_deleted', 0)->where('status', 2)->count() > 0){
            //get payment record
            $payment = Payment::where('id', $request->id)->first();

            //get business disbursement commission
            $commission_id = Business::where('id', $payment->business_id)->first()->disbursement_commission_id;
            $commission_charges = PaymentCommission::where('id',$commission_id)->first()->calculations;
            $calculated_commission = ($payment->received_amount * $commission_charges);

            //calculate the total amount to be deducted from the business account
            $total_amount = $payment->received_amount + $calculated_commission;

            $phone_number_formatted = substr($payment->phone_number, -9);

            //check if business disbursement balance is enough for the task
            if(CurrentBalance::where('business_id', $payment->business_id)->first()->disbursement >= $total_amount){

                if($payment->payment_channel == "Airtel Money") {

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://openapi.airtel.africa/auth/oauth2/token',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '{
                            "client_id": "19fd47b3-6c68-4759-b360-f0f2c4592e07",
                            "client_secret": "4dd9fea0-3c5d-4df5-a9ff-369bd16f511c",
                            "grant_type": "client_credentials"
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Cookie: SERVERID=s115'
                        ),
                    ));

                    $token_response = curl_exec($curl);

                    curl_close($curl);

                    $string_response = $token_response;
                    $json = json_decode($string_response);
                    $token = $json->access_token;

                    if ($token) {

                        $send_commission_to_agent = Http::withHeaders([
                            'X-Currency' => 'ZMW',
                            'X-Country' => 'ZM',
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $token
                        ])->post('https://openapi.airtel.africa/standard/v1/disbursements/', [
                            "payee" => [
                                "currency" => "ZMW",
                                "msisdn" => $phone_number_formatted,
                                "name" => "Customer ".$payment->customer_id,
                            ],
                            "reference" => "Agent Commission",
                            "pin" => "Zt6uoovDi+npFfsvmyzTZGAS7Hxi1gNmq+pZFUNflq6fNkzPFqL8sc/hNvnX0bhIE8l3FmXNZqXjOnI3XvlcPIf3nhtYHvqbiUtpW2XV+63LXi52LpPDSA3BywlGVxhQP1tzVl+kGCXQgXT+K7rFqI2U4c3bDPSEyTp2bweVPsY=",
                            "transaction" => [
                                "amount" => $payment->received_amount,
                                "id" => $payment->payment_reference_number,
                                "type" => "B2B"
                            ]
                        ]);

                        $status_state = $send_commission_to_agent->status();
                        $status_json = $send_commission_to_agent->json();

                        if($status_state == 200) {
                            if ($status_json['status']['code'] == "200") {
                                //successfull refund from airtel

                                //get current balance
                                $old_current_payment_balance = CurrentBalance::where('business_id',$payment->business_id)->first()->payments;

                                //current balance transaction calculations
                                $new_current_balance = $old_current_payment_balance - $total_amount;

                                $update_payments_balance = CurrentBalance::where('business_id',$payment->business_id)->update([
                                    "payments" => $new_current_balance
                                ]);

                                //save refund record
                                $new_refund_record = Refund::create([
                                    'business_id' => $payment->business_id,
                                    'customer_id' => $payment->customer_id,
                                    'account_number' => $payment->account_number,
                                    'business_name' => $payment->business_name,
                                    'payment_channel' => $payment->payment_channel,
                                    'payment_reference_number' => $payment->payment_reference_number,
                                    'refund_reference_number' => $payment->payment_reference_number,
                                    'phone_number' => $payment->phone_number,
                                    'reason' => $request->refundReason,
                                    'refunded_amount' => $payment->received_amount,
                                    'commission_charged' => $calculated_commission,
                                    'total_amount' => $total_amount, // refunded_amount + commission_charged
                                    'status' => 2//2 for success
                                ]);

                                $new_refund_record->save();

                                //update payment record
                                $update_payment_record = Payment::where('id', $payment->id)->update([
                                    "is_refunded" => 1
                                ]);

                                //send out notifications
                                $business_contact = Business::where('id', $payment->business_id)->first();
                                //send confirmation email to the business client
                                Mail::to($business_contact->business_email)->send(new PaymentConfirmationMail($business_contact->business_name, $payment->received_amount, $payment->phone_number, $payment->payment_reference_number, "to customer"));

                                //get frontend url
                                $frontend_url = FrontEndUrl::first()->domain;
                                //receipt url
                                $receipt_url = $frontend_url.'/'.$payment->short_url;

                                //send confirmation sms
                                $url_encoded_message = urlencode($business_contact->business_name." has refunded you of ZMW" . $payment->received_amount .". Download your receipt at: " . $receipt_url);

                                $sendSMS = Http::withoutVerifying()
                                     ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $payment->phone_number . '&api_key=121231313213123123');

                               /* $sendZamtelAPI = Http::withoutVerifying()
                                    ->post('https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/38050146d5e214e9731bc939e9668c4e/contacts/'.$payment->phone_number.'/senderId/GeePay/message/'.$url_encoded_message);
*/

                                $custom_response = [
                                    "success" => true,
                                    "message" => "transaction processed successfully",
                                    "date" => now()
                                ];

                                return response()->json($custom_response, 200);
                            }else{
                                $custom_response = [
                                    "success" => false,
                                    "message" => "failed to send refund due to network, will try again later",
                                    "reason" => $status_json
                                ];

                                return response()->json($custom_response,400);
                            }
                        }else{
                            $custom_response = [
                                "success" => false,
                                "message" => "failed to send refund due to network, will try again later",
                                "reason" => $status_json
                            ];

                            return response()->json($custom_response,400);
                        }
                    }
                }


            }else{
                $custom_response = [
                    "success" => false,
                    "message" => "You do not sufficient credit in both payment or disbursement accounts to make a refund"
                ];

                return response()->json($custom_response, 400);
            }


        }else{
            $custom_response = [
                "success" => false,
                "message" => "You can not perform this task"
            ];

            return response()->json($custom_response, 400);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Refund $refund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refund $refund)
    {
        //
    }
}
