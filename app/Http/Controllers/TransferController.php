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
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function api_business_transfers(Request $request){
        $payments = Transfer::where('business_id', $request->input('nob'))->where('is_deleted', 0);

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $payments = Transfer::where('business_id', $request->input('nob'))->where('is_deleted', 0)->where('id', 'like', "%$search%")
                ->orWhere('payment_reference_number', 'like', "%$search%")
                ->orWhere('payment_channel', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('received_amount', 'like', "%$search%")
                ->orWhere('created_at', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        }

        $pageSize = $request->input('pageSize', 10);

        return $payments->orderBy('updated_at','DESC')->paginate($pageSize);
    }

    function generatePaymentReferenceNumber() {
        $prefix = 'T'; // Prefix for the account number
        $suffix = time(); // Suffix for the account number (UNIX timestamp)

        // Generate a random number between 1000 and 9999
        $random = rand(100000, 999999);

        // Combine the prefix, random number, and suffix to form the account number
        $raw_payment_reference_number = $prefix . $random . $suffix;

        $payment_reference_number = substr($raw_payment_reference_number, 0, 24);

        // Check if the account number already exists in the database
        if (DB::table('payments')->where('payment_reference_number', $payment_reference_number)->exists()) {
            // If the account number already exists, generate a new one recursively
            return $this->generatePaymentReferenceNumber();
        }

        return $payment_reference_number;
    }

    function generate_short_url()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $res = "";
        for ($i = 0; $i < 5; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        // Check if the short url already exists in the database
        if (DB::table('transfers')->where('short_url', $res)->exists()) {
            // If the short url already exists, generate a new one recursively
            return $this->generate_short_url();
        }

        return $res;
    }

    public function api_disbursement_balance (Request $request){
        $total_disbursed = Transfer::where("business_id",$request->business_id)->sum('received_amount');
        $current_disbursement_balance = Deposit::where("business_id",$request->business_id)->first()->amount;
        $custom_response = [
            "success" => true,
            "message" => "Balance fetched successfully",
            "current_balance" => $current_disbursement_balance,
            "total_disbursed" => $total_disbursed
        ];
        return response()->json($custom_response,200);
    }

    public function api_send_mobile_money(Request $request)
    {
        //get business disbursement commission
        $commission_id = Business::where('id', $request->business_id)->first()->disbursement_commission_id;
        $commission_charges = PaymentCommission::where('id',$commission_id)->first()->calculations; //0.015
        $calculated_commission = ($request->enteredAmount * $commission_charges); //3 *0.015

        //calculate the total amount to be deducted from the business account
        $total_amount = $request->enteredAmount + $calculated_commission;

        $phone_number_formatted = substr($request->enteredPhoneNumber, -9);
        $transfer_ref_number = $this->generatePaymentReferenceNumber();

        //check if the current disbursement balance is enough
        if(CurrentBalance::where('business_id', $request->business_id)->first()->disbursement >= $total_amount){
            if($request->paymentMethod == "Airtel Money" || $request->paymentMethod == "MTN Money" || $request->paymentMethod == "Zamtel Money") {

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
                            "name" => "Customer ".$request->customerName
                        ],
                        "reference" => $transfer_ref_number,
                        "pin" => "Zt6uoovDi+npFfsvmyzTZGAS7Hxi1gNmq+pZFUNflq6fNkzPFqL8sc/hNvnX0bhIE8l3FmXNZqXjOnI3XvlcPIf3nhtYHvqbiUtpW2XV+63LXi52LpPDSA3BywlGVxhQP1tzVl+kGCXQgXT+K7rFqI2U4c3bDPSEyTp2bweVPsY=",
                        "transaction" => [
                            "amount" => $request->enteredAmount,
                            "id" => $this->generatePaymentReferenceNumber(),
                            "type" => "B2B"
                        ]
                    ]);

                    $status_state = $send_commission_to_agent->status();
                    $status_json = $send_commission_to_agent->json();

                    if($status_state == 200) {

                        if ($status_json['status']['code'] == "200") {

                            //Update Disbursable Balance
                            //get current balance
                            $old_current_disbursement_balance = CurrentBalance::where('business_id',$request->business_id)->first()->disbursement;

                            //current balance transaction calculations
                            $new_current_balance = $old_current_disbursement_balance - $total_amount;

                            $update_payments_balance = CurrentBalance::where('business_id',$request->business_id)->update([
                                "disbursement" => $new_current_balance
                            ]);

                            $short_url = $this->generate_short_url();
                            $business=Business::where('id',$request->business_id)->first();
                            //successfull refund from airtel
                            $new_payment = Transfer::create([
                                "business_id" => $request->business_id,
                                "payment_method_id" => 1,
                                "payment_channel" => $request->paymentMethod,
                                "business_name" => $business->business_name,
                                "account_number" => $business->account_number,
                                "payment_reference_number" => $transfer_ref_number,
                                "txn_number" => $transfer_ref_number,
                                "phone_number" => "260".$phone_number_formatted,
                                "description" => $request->description,
                                "received_amount" => $request->enteredAmount,
                                "commission_charged" => $calculated_commission,
                                "total_amount" => $total_amount,
                                "short_url" => $short_url,
                                "status" => 2 //successful
                            ]);

                            $new_payment->save();

                            //send out notifications
                            $business_contact = Business::where('id', $request->business_id)->first();
                            //send confirmation email to the business client
                            Mail::to($business_contact->business_email)->send(new PaymentConfirmationMail($business_contact->business_name, $request->enteredAmount, $request->enteredPhoneNumber, $transfer_ref_number, "to customer"));

                            //get frontend url
                            $frontend_url = FrontEndUrl::first()->domain;
                            //receipt url
                            $receipt_url = $frontend_url.'/t/'.$short_url;

                            //send confirmation sms
                            $url_encoded_message = urlencode($business_contact->business_name." has sent you a sum of ZMW" . $request->enteredAmount .". Download your receipt at: " . $receipt_url);

                            $sendSMS = Http::withoutVerifying()
                                ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $request->enteredPhoneNumber . '&api_key=121231313213123123');

                            /*$sendZamtelAPI = Http::withoutVerifying()
                                ->post('https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/38050146d5e214e9731bc939e9668c4e/contacts/'.$request->enteredPhoneNumber.'/senderId/GeePay/message/'.$url_encoded_message);*/

                            $custom_response = [
                                "success" => true,
                                "message" => "transaction processed successfully",
                                "date" => now(),
                                "total amount" => $total_amount,
                                "commission" => $calculated_commission
                            ];

                            return response()->json($custom_response, 200);
                        }elseif($status_json['status']['code'] == "500"){
                            $custom_response = [
                                "success" => false,
                                "message" => $status_json['status']['message'],
                                "reason" => $status_json
                            ];

                            return response()->json($custom_response,400);
                        }else{
                            $custom_response = [
                                "success" => false,
                                "message" => "failed to transfer money due to network, will try again later",
                                "reason" => $status_json
                            ];

                            return response()->json($custom_response,400);
                        }
                    }else{
                        $custom_response = [
                            "success" => false,
                            "message" => "failed to transfer money due to network, will try again later",
                            "reason" => $status_json
                        ];

                        return response()->json($custom_response,400);
                    }
                }
            }else{
                $custom_response = [
                    "success" => false,
                    "message" => "Invalid Mobile Money Number"
                ];

                return response()->json($custom_response, 400);
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "You do not sufficient credit in your disbursement account to transfer money"
            ];

            return response()->json($custom_response, 400);
        }
    }
    public function index()
    {
        $all_transfers = Transfer::where('status', 2)->get();
        return view('transfers.index', compact('all_transfers'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transfer $transfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $delete_selected = Transfer::where('id', $request->id)->update([
            "is_deleted" => 1
        ]);

        $custom_response = [
            "success" => true,
            "message" => "payment transaction deleted successfully"
        ];

        return response()->json($custom_response, 200);
    }
}
