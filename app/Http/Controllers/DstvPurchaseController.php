<?php

namespace App\Http\Controllers;

use App\Models\ConsumerBalance;
use App\Models\ConsumerCommission;
use App\Models\ConsumerTransaction;
use App\Models\DstvPackage;
use App\Models\GoTvPackage;
use App\Models\PaymentCommission;
use App\Models\ShowMaxPackage;
use App\Models\TopstarPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DstvPurchaseController extends Controller

{
    function generatePaymentReferenceNumber()
    {
        $prefix = 'TRN'; // Prefix for the account number
        $suffix = time(); // Suffix for the account number (UNIX timestamp)

        // Generate a random number between 1000 and 9999
        $random = rand(1000000000, 9999999999);

        // Combine the prefix, random number, and suffix to form the account number
        $raw_payment_reference_number = $prefix . $random . $suffix;

        $payment_reference_number = substr($raw_payment_reference_number, 0, 24);

        // Check if the payment reference number already exists in the database
        if (DB::table('consumer_transactions')->where('payment_reference_number', $payment_reference_number)->exists()) {
            // If the payment reference number already exists, generate a new one recursively
            return $this->generatePaymentReferenceNumber();
        }

        return $payment_reference_number;
    }

    public function process_konse_konse_get_dstv_client_details(Request $request)
    {
        $request->validate([
            'account_no' => 'required',
        ]);

        $voucher_id = $request->voucher_id;
        $voucher_type = $request->voucher_type;
        $amount = $request->amount;
        $voucher_name = $request->voucher_name;

        if($request->voucher_type == "null" && $request->record_id == 1){
            if($request->provider_id == 1) //DSTV
            {
                $voucher_details = DstvPackage::where("is_active", 1)->first();

                $voucher_id = $voucher_details->voucher_id;
                $voucher_type = $voucher_details->voucher_type;
                $amount = $voucher_details->voucher_value;
                $voucher_name =  $voucher_details->name;

            }elseif ($request->provider_id == 2) //ShowMax
            {
                $voucher_details = ShowMaxPackage::where("is_active", 1)->first();

                $voucher_id = $voucher_details->voucher_id;
                $voucher_type = $voucher_details->voucher_type;
                $amount = $voucher_details->voucher_value;
                $voucher_name =  $voucher_details->name;

            }elseif ($request->provider_id == 3) //GoTv
            {
                $voucher_details = GoTvPackage::where("is_active", 1)->first();

                $voucher_id = $voucher_details->voucher_id;
                $voucher_type = $voucher_details->voucher_type;
                $amount = $voucher_details->voucher_value;
                $voucher_name =  $voucher_details->name;

            }elseif ($request->provider_id == 4) //Topstar
            {
                $voucher_details = TopstarPackage::where("is_active", 1)->first();

                $voucher_id = $voucher_details->voucher_id;
                $voucher_type = $voucher_details->voucher_type;
                $amount = $voucher_details->voucher_value;
                $voucher_name =  $voucher_details->name;

            }
        }

        //converted from int to absolute value (e.g from 1000 to 10.00)
        $converted_amount_from_int = floatval($amount)/100;

        //calculate the charge
        $charge_details = PaymentCommission::where("category", "DSTV")->first();

        $convenience_fee = (((floatval($charge_details->cgrate_percentage) + floatval($charge_details->geepay_percentage))/100) * floatval($converted_amount_from_int)) + (floatval($charge_details->cgrate_fixed_charge) + floatval($charge_details->geepay_fixed_charge));

        $total_amount = floatval($converted_amount_from_int) + $convenience_fee;

        //commission calculations
        $cgrate_percentage = (floatval($charge_details->cgrate_percentage)/100) * floatval($converted_amount_from_int);
        $geepay_percentage = (floatval($charge_details->geepay_percentage)/100) * floatval($converted_amount_from_int);
        $cgrate_fixed_charge = $charge_details->cgrate_fixed_charge;
        $geepay_fixed_charge = $charge_details->geepay_fixed_charge;

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
                <kon:getBillCustomerName>
                    <!--Optional:-->
                    <serviceProvider>'.$request->service_provider.'</serviceProvider>
                    <!--Optional:-->
                    <billPaymentAccountNumber>' . $request->account_no . '</billPaymentAccountNumber>
                </kon:getBillCustomerName>
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

        $responseCode = (string)$xml->xpath('//env:Envelope/env:Body/ns2:getBillCustomerNameResponse/return/responseCode')[0];
        $responseMessage = (string)$xml->xpath('//env:Envelope/env:Body/ns2:getBillCustomerNameResponse/return/responseMessage')[0];

        if ($responseCode == '0') {
            $billCustomerName = (string)$xml->xpath('//env:Envelope/env:Body/ns2:getBillCustomerNameResponse/return/billCustomerName')[0];

            $phone_number = Auth::user()->phone_number;

            if($request->is_self == 1)
            {
                $phone_number = $request->phone_number;
            }

            //Show transaction details
            $customResponse = [
                'success' => true,
                'responseCode' => $responseCode,
                'responseMessage' => $responseMessage,
                'billCustomerName' => $billCustomerName,
                'account_no' => $request->account_no,
                'paymentMethod' => $request->payment_method,
                'phoneNumber' => $phone_number,
                'is_self' => $request->is_self,
                'voucher_name' => $voucher_name,
                'voucher_id' => $voucher_id,
                'voucher_type' => $voucher_type,
                'amount' => $amount,
                'service_provider' => $request->service_provider,
                'convenience_fee' => number_format($convenience_fee,2),
                'total_amount' => number_format($total_amount,2),
                'cgrate_percentage' => $cgrate_percentage,
                'geepay_percentage' => $geepay_percentage,
                'cgrate_fixed_charge' => $cgrate_fixed_charge,
                'geepay_fixed_charge' => $geepay_fixed_charge

            ];
            return response()->json($customResponse);
        } else {
            $customResponse = [
                'success' => false,
                'responseCode' => $responseCode,
                'responseMessage' => $responseMessage
            ];
            return response()->json($customResponse);
        }


    }

    public function dstv_topup(Request $request)
    {
        // Retrieve the amount and meter number from the request

        $request->validate([
            'billName'=> 'required',
            'accNo' => 'required',
            'phoneNumbr' => 'required',
            'isSelf' => 'required',
            'voucher_name' => 'required',
            'voucher_id' => 'required',
            'voucher_type' => 'required',
            'amount' => 'required',
            'service_provider' => 'required'
        ]);

        $ref_no = $this->generatePaymentReferenceNumber();
        $amount = $request->amount / 100;
        $account_no = $request->accNo;
        $payment_method = $request->payment_method;
        $payment_channel = $request->payment_method;
        $phone_number = $request->phoneNumbr;
        $service_provider = $request->service_provider;
        $convenience_fee  = $request->convenience_fee;
        $total_amount = $request->total_amount;
        $cgrate_percentage = $request->cgrate_percentage;
        $geepay_percentage = $request->geepay_percentage;
        $cgrate_fixed_charge = $request->cgrate_fixed_charge;
        $geepay_fixed_charge = $request->geepay_fixed_charge;

        if ($payment_method == 'mm') {
            //check the payment_channel
            if ($payment_channel != 'Airtel' || $payment_channel != 'MTN' || $payment_channel != 'Zamtel') {
                //send payment request to Airtel money (create a function for airtel money to confirm the payment and call the function to process zesco purchas)
                //provoke ZESCO payment intent
                $this->primeNetDStvPayment($phone_number, $amount, $ref_no);

                //save consumer transaction payment intent
                $zesco_payment_intent = ConsumerTransaction::create([
                    "consumer_id" => Auth::user()->id,
                    "consumer_name" => Auth::user()->name,
                    "phone_number" => Auth::user()->phone_number,
                    "payment_reference_number" => $ref_no,
                    "channel" => "mm",
                    "method" => $payment_channel,
                    "type" => "pay",
                    "partner" => "third-party",
                    "amount" => $amount,
                    "status" => 0,
                    "custom_message" => "ZESCO units"
                ]);

                $zesco_payment_intent->save();

                $custom_response = [
                    "success" => true,
                    "message" => "Payment Intent has been created successfully",
                    "payment_reference_number" => $ref_no,
                    "amount" => $amount,
                    "account_no" => $account_no
                ];

                //return response
                return response()->json($custom_response, 201);

            } else {
                $custom_response = [
                    'success' => false,
                    'message' => 'Invalid payment channel.'
                ];
                return response()->json($custom_response, 400);
            }
        } elseif ($payment_method == 'GeePay') {

            $consumer_balance = floatval(ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance);

            //First check the balance of the account for the customer
            if ($consumer_balance > $total_amount) {

                //save payment intent
                $dstv_payment_intent = ConsumerTransaction::create([
                    "consumer_id" => Auth::user()->id,
                    "consumer_name" => Auth::user()->name,
                    "phone_number" => Auth::user()->phone_number,
                    "payment_reference_number" => $ref_no,
                    "channel" => $payment_method,
                    "method" => $payment_method,
                    "type" => "pay ".$service_provider,
                    "partner" => $service_provider,
                    "amount" => $amount,
                    "status" => 1, //pending
                    "custom_message" => $service_provider
                ]);

                $dstv_payment_intent->save();

                //change the current balance
                $new_current_balance = $consumer_balance - $total_amount;

                //send data to Konse Konse to process DSTV purchase
                return $this->process_konse_konse_dstv_purchase($account_no, $ref_no, $amount, $request->voucher_type, $request->voucher_id, $request->is_fixed,$request->isSelf, $request->phoneNumbr,$service_provider,$convenience_fee, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge,$new_current_balance);

            } else {
                $custom_response = [
                    "success" => false,
                    "message" => "No enough balance to perform this transaction"
                ];

                return response()->json($custom_response, 400);
            }
        } elseif ($payment_method == 'vm') {
            $custom_response = [
                "success" => false,
                "message" => "VISA/Mastercard"
            ];

            return response()->json($custom_response, 400);
        } elseif ($payment_method == 'Bank') {
            $custom_response = [
                "success" => false,
                "message" => "Bank"
            ];

            return response()->json($custom_response, 400);
        } else {
            $customResponse = [
                'success' => false,
                'Message' => 'Invalid payment method'
            ];
            return response()->json($customResponse, 400);
        }


    }

    function process_konse_konse_dstv_purchase($account_no, $ref_no, $amount, $voucher_type, $voucher_id, $is_fixed, $is_self, $phone_number,$service_provider,$convenience_fee, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge,$new_current_balance)
    {

        $isfixed_bool = false;

        if($is_fixed == 1){
            $isfixed_bool = true;
        }

        //credentials
        $konse_konse_url = env("KONSE_KONSE_URL");
        $konse_konse_username = env("KONSE_KONSE_USERNAME");
        $konse_konse_password = env("KONSE_KONSE_PASSWORD");

        //convert bill amount
        $bill = $amount * 100;

        Log::info("Request to TV",["543"=>'<Voucher>
                <isFixed>'.$isfixed_bool.'</isFixed>
                <receipient>' . $account_no . '</receipient>
                <serviceProvider>'.$service_provider.'</serviceProvider>
                <transactionReference>' . $ref_no . '</transactionReference>
                <voucherType>'.$voucher_type.'</voucherType>
                <voucherValue>' . $bill . '</voucherValue>
                <voucherId>' . $voucher_id . '</voucherId>
            </Voucher>']);

        //check if the transaction is still in pending state
        if (ConsumerTransaction::where('payment_reference_number', $ref_no)->where('status', 1)->count() > 0 && floatval(ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance) > floatval($amount)) {
            //curll request to SOAP Konse Konse
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
            <Voucher>
                <isFixed>'.$isfixed_bool.'</isFixed>
                <receipient>' . $account_no . '</receipient>
                <serviceProvider>'.$service_provider.'</serviceProvider>
                <transactionReference>' . $ref_no . '</transactionReference>
                <voucherType>'.$voucher_type.'</voucherType>
                <voucherValue>' . $bill . '</voucherValue>
                <voucherId>' . $voucher_id . '</voucherId>
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
                    'Accept: application/soap+xml, application/dime, multipart/related, text/*',
                    'Content-Type: text/xml',
                    'SOAPAction: ""'
                ),
            ));

            $response = curl_exec($curl);

            Log::info("TV Topup Response",["543"=>json_encode($response)]);

            curl_close($curl);

            $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

            $xml->registerXPathNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('ns2', 'http://konik.cgrate.com');

            $responseCode = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/responseCode')[0];
            $responseMessage = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/responseMessage')[0];


            if ($responseCode == '0') {
                $purchaseId = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/purchaseId')[0];
                $voucherSerialNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseVoucherResponse/return/voucherSerialNumber')[0];

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

                //balance update
                $old_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;

                $new_balance = floatval($old_balance) - floatval($amount);

                $update_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->update([
                    "balance" => $new_balance
                ]);

                if($is_self == 1){

                    //send sms notification with meter no, amount, token and KWhAmount
                    $url_encoded_message = urlencode("You have successfully paid your ".$service_provider." subscription fee of K" . $amount . " for the Account:" . $account_no . ". Your active bouquet is " . $voucher_type . ". Txn: ".$ref_no);

                    $sendSMS = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . Auth::user()->phone_number . '&api_key=121231313213123123');

                }else{
                    //send sms notification with meter no, amount, token and KWhAmount
                    $url_encoded_message = urlencode( Auth::user()->name. "has bought you DSTV subscription of K" . $amount . " for the Account:" . $account_no . ". Your active bouquet is " . $voucher_type . ". Txn: ".$ref_no);

                    $sendSMS = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $phone_number . '&api_key=121231313213123123');

                }

                //update the consumer transaction meta data column
                $meta_data = [
                    'code' => $responseCode,
                    'responseMessage' => $responseMessage,
                    'purchaseId' => $purchaseId,
                    'voucherSerialNumber' => $voucherSerialNumber,
                    'ref_no' => $ref_no
                ];

                //updating the database
                ConsumerTransaction::where('payment_reference_number', $ref_no)->where('status', 1)->update([
                    "status" => 2,
                    "meta_data" => json_encode($meta_data)
                ]);

                //custom response
                $custom_response = [
                    'success' => true,
                    'message' => "TV subscription bought successfully",
                    'purchaseId' => $purchaseId,
                    'voucherSerialNumber' => $voucherSerialNumber,
                    'ref_no' => $ref_no,
                    'account_no' => $account_no,
                    'service_provider' => $service_provider,
                    'voucher_name' => $voucher_type
                ];

                return response()->json($custom_response, 200);

            }elseif ($responseCode == '1'){ //insufficient Konse Konse Balance

                $meta_data = [
                    'code' => $responseCode,
                    'responseMessage' => $responseMessage,
                    'ref_no' => $ref_no
                ];

                //updating the database
                ConsumerTransaction::where('payment_reference_number', $ref_no)->where('status', 1)->update([
                    "status" => 0, //failed
                    "meta_data" => json_encode($meta_data)
                ]);

                $custom_response = [
                    "success" => false,
                    "message" => "Your transaction has been reversed. Try again later"
                ];

                return response()->json($custom_response, 400);
            } else {
                //updating the database
                ConsumerTransaction::where('payment_reference_number', $ref_no)->where('status', 1)->update([
                    "status" => 0,
                    "meta_data" => "Something wrong happened"
                ]);

                $custom_response = [
                    "success" => false,
                    "message" => "Something wrong happened. Try again Later"
                ];

                return response()->json($custom_response, 400);
            }
        } else {
            //the transaction was already processed
            $custom_response = [
                "success" => false,
                "message" => "You have insufficient history to perform this transaction"
            ];

            return response()->json($custom_response, 400);
        }
    }

    function primeNetDStvPayment($phone_number, $bill, $ref_no): void
    {
        $amount_to_query = $bill / 100;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authorization' => env('PRIMENET_X_AUTH_TEST'),
            'Content-Type' => 'application/json',
        ])->post('https://api.primenetpay.com:9001/api/v2/transaction/collect', [
            'payer_number' => $phone_number,
            'external_reference' => $ref_no,
            'payment_narration' => 'Zesco purchase',
            'currency' => 'ZMW',
            'amount' => $amount_to_query,
        ]);

        $log_message = 'Primenet';

        $data = [
            "phone_number" => $phone_number,
            "response" => $response->json()
        ];

        Log::info($log_message, $data);
    }

}
