<?php

namespace App\Http\Controllers;

use App\Models\ConsumerBalance;
use App\Models\ConsumerCommission;
use App\Models\ConsumerTransaction;
use App\Models\PaymentCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DirectTalktimeController extends Controller
{
    function generatePaymentReferenceNumber()
    {
        $prefix = 'TRN'; // Prefix for the account number
        $suffix = time(); // Suffix for the account number (UNIX timestamp)

        // Generate a random number between 1000 and 9999
        $random = rand(100000, 999999);

        // Combine the prefix, random number, and suffix to form the account number
        $raw_payment_reference_number = $prefix .".". $random .".". $suffix;

        $payment_reference_number = substr($raw_payment_reference_number, 0, 24);

        // Check if the payment reference number already exists in the database
        if (DB::table('consumer_transactions')->where('payment_reference_number', $payment_reference_number)->exists()) {
            // If the payment reference number already exists, generate a new one recursively
            return $this->generatePaymentReferenceNumber();
        }

        return $payment_reference_number;
    }

    function process_konse_konse_talktime_direct_top_up($phone_number, $mobile_operator, $ref_no, $amount, $option, $total_amount, $payer_current_balance, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge)
    {
        Log::info("Konse Konse Talktime to ".$phone_number);

        $network = "Airtel";

        if($mobile_operator == "MTN Airtime")
        {
            $network = "MTN";
        }elseif ($mobile_operator == "ZAMTEL Airtime")
        {
            $network = "Zamtel";
        }

        //format amount too integer
        $bill = $amount * 100;

        //credentials
        $konse_konse_url = env("KONSE_KONSE_URL");
        $konse_konse_username = env("KONSE_KONSE_USERNAME");
        $konse_konse_password = env("KONSE_KONSE_PASSWORD");

        $curl = curl_init();

        $xmlPayload = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:kon="http://konik.cgrate.com">
    <soapenv:Header>
        <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" soapenv:mustUnderstand="1">
            <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="' . $konse_konse_username . '">
                <wsse:Username>' . $konse_konse_username . '</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $konse_konse_password . '</wsse:Password>
            </wsse:UsernameToken>
        </wsse:Security>
    </soapenv:Header>
    <soapenv:Body>
        <ns2:purchaseVoucher xmlns:ns2="http://konik.cgrate.com">
         <!--Optional:-->
         <Voucher>
            <isFixed>false</isFixed>
            <receipient>' . $phone_number . '</receipient>
            <serviceProvider>' . $network . '</serviceProvider>
            <transactionReference>' . $ref_no . '</transactionReference>
            <voucherValue>' . $bill . '</voucherValue>
         </Voucher>
      </ns2:purchaseVoucher>
    </soapenv:Body>
</soapenv:Envelope>';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $konse_konse_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmlPayload,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/soap+xml,application/dime,multipart/related,text/*',
                'Content-Type: text/xml',
                // Specify the appropriate SOAPAction value if required by the service
                'SOAPAction: ""',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

        $xml->registerXPathNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns2', 'http://konik.cgrate.com');

        $responseCode = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/responseCode')[0];
        $responseMessage = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/responseMessage')[0];


        if ($responseCode == '0') {
            $purchaseId = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/purchaseId')[0];
            $voucherSerialNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/voucherSerialNumber')[0];

            //change the current balance
            $new_current_balance = floatval($payer_current_balance) - $total_amount;

            $update_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->update([
                "balance" => $new_current_balance
            ]);

            //consumer commission
            $consumer_commission = ConsumerCommission::create([
                "consumer_id" => Auth::user()->id,
                "transaction_reference_number" => $ref_no,
                "cgrate_percentage" => $cgrate_percentage,
                "geepay_percentage" => $geepay_percentage,
                "cgrate_fixed_charge" => $cgrate_fixed_charge,
                "geepay_fixed_charge" => $geepay_fixed_charge
            ]);

            $consumer_commission->save();

            if($option == "self")
            {
                //save the records for transaction
                $sender_transaction_details = ConsumerTransaction::create([
                    "consumer_id" => Auth::user()->id,
                    "consumer_name" => Auth::user()->name,
                    "payment_reference_number" => $ref_no,
                    "type" => "pay", //send,deposit,transfer,pay
                    "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                    "partner_type" => "Airtel Airtime", //Wallet, Business,Agent,third-party name
                    "amount" => $amount,
                    "status" => 2, //failed
                    "channel" => "GeePay",
                    "custom_message" => "Bought Talktime for self",
                    "phone_number" => Auth::user()->phone_number
                ]);

                $sender_transaction_details->save();

                //send sms notification with meter no, amount, token and KWhAmount
                $url_encoded_message = urlencode("Your have successfully bought ZMW" . $amount . " of " . $mobile_operator . " talktime and your transaction number is " . $ref_no);

                $sendSMS = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . Auth::user()->phone_number . '&api_key=121231313213123123');

            }else{

                //save the records for transaction
                $sender_transaction_details = ConsumerTransaction::create([
                    "consumer_id" => Auth::user()->id,
                    "consumer_name" => Auth::user()->name,
                    "payment_reference_number" => $ref_no,
                    "type" => "pay", //send,deposit,transfer,pay
                    "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                    "partner_type" => "Airtel Airtime", //Wallet, Business,Agent,third-party name
                    "amount" => $amount,
                    "status" => 2, //failed
                    "channel" => "GeePay",
                    "custom_message" => "Bought Talktime for self",
                    "phone_number" => $phone_number
                ]);

                $sender_transaction_details->save();

                //send sms notification with meter no, amount, token and KWhAmount
                $url_encoded_message = urlencode("Your have successfully bought ZMW" . $amount . " of " . $mobile_operator . " talktime for ".$phone_number.". Txn: " . $ref_no);

                $sendSMS = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . Auth::user()->phone_number . '&api_key=121231313213123123');

                //send sms notification with meter no, amount, token and KWhAmount
                $url_encoded_message_to_other = urlencode(Auth::user()->name." has bought you ZMW" . $amount . " of " . $mobile_operator . " talktime. Txn: " . $ref_no.".\n\n Get quick loans from GoodFellow today");

                $sendSMSOther = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message_to_other . '.+&shortcode=2343&sender_id=GeePay&phone=' . $phone_number . '&api_key=121231313213123123');

            }

            $customResponse = [
                'success' => true,
                'message' => 'You have successfully purchased ' . $mobile_operator . ' talktime.',
                'konseKonseMessage' => $responseMessage,
                'purchaseId' => $purchaseId,
                'voucherSerialNumber' => $voucherSerialNumber
            ];
            return response()->json($customResponse, 200);
        } else {
            $customResponse = [
                'success' => false,
                'message' => 'Something went wrong, try again.'
            ];
            return response()->json($customResponse, 400);
        }

    }

    public function handle_client_talktime_direct_topup(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'option' => 'required',
            'payment_method' => 'required'
        ]);

        if ($request->payment_method == 'mm') {
            $custom_response = [
                "success" => true,
                "message" => "this is MM option"
            ];

            return response()->json($custom_response, 200);

        } elseif ($request->payment_method == 'GeePay') {

            $charge_details = PaymentCommission::where("category", $request->mobile_operator)->first();

            $convenience_fee = (((floatval($charge_details->cgrate_percentage) + floatval($charge_details->geepay_percentage))/100) * floatval($request->amount)) + (floatval($charge_details->cgrate_fixed_charge) + floatval($charge_details->geepay_fixed_charge));

            $total_amount = floatval($request->amount) + $convenience_fee;

            //commission calculations
            $cgrate_percentage = (floatval($charge_details->cgrate_percentage)/100) * floatval($request->amount);
            $geepay_percentage = (floatval($charge_details->geepay_percentage)/100) * floatval($request->amount);
            $cgrate_fixed_charge = $charge_details->cgrate_fixed_charge;
            $geepay_fixed_charge = $charge_details->geepay_fixed_charge;


            $amount = $request->amount;
            //First check the balance of the account for the customer
            if (floatval(ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance) >= ($total_amount)) {
                //get payer current balance
                $payer_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;

                Log::info("Buying talk time to ".$request->phone_number);

                if ($request->option == 'self') {
                    //buying for authenticated number
                    $phone_number = Auth::user()->phone_number;
                    $mobile_operator = $request->mobile_operator;
                    $ref_no = $this->generatePaymentReferenceNumber();


                    return $this->process_konse_konse_talktime_direct_top_up($phone_number, $mobile_operator, $ref_no, $amount, $request->option,$total_amount, $payer_current_balance, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge);

                } elseif($request->option == 'other') {
                    //buying for other
                    $phone_number = $request->phone_number;
                    $mobile_operator = $request->mobile_operator;
                    $ref_no = $this->generatePaymentReferenceNumber();

                    //change the current balance
                    $new_current_balance = $payer_current_balance - $amount;

                    $update_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->update([
                        "balance" => $new_current_balance
                    ]);

                    return $this->process_konse_konse_talktime_direct_top_up($phone_number, $mobile_operator, $ref_no, $amount, $request->option,$total_amount, $payer_current_balance, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge);
                }
            } else {
                //customResponse for insufficient balance
                $custom_response = [
                    "success" => false,
                    "message" => "You do not have suffiecient balance to perform this task"
                ];

                return response()->json($custom_response, 400);
            }
        } elseif ($request->payment_method == 'vm') {
            //customResponse for insufficient balance
            $custom_response = [
                "success" => true,
                "message" => "this is Visa/Mastercard"
            ];

            return response()->json($custom_response, 200);
        }

    }
}
