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

class ZescoPurchaseController extends Controller

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

    public function process_konse_konse_get_zesco_client_details(Request $request)
    {

        //converted from int to absolute value (e.g from 1000 to 10.00)
        $converted_amount_from_int = floatval($request->amount);

        //calculate the charge
        $charge_details = PaymentCommission::where("category", "ZESCO")->first();

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

        $request->validate([
            'meter_no' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'is_self' => 'required'
        ]);

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
                    <serviceProvider>Zesco</serviceProvider>
                    <!--Optional:-->
                    <billPaymentAccountNumber>' . $request->meter_no . '</billPaymentAccountNumber>
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

            if($request->is_self == 0){
                $phone_number = $request->phone_number;
            }
            //Show transaction details
            $customResponse = [
                'success' => true,
                'responseCode' => $responseCode,
                'responseMessage' => $responseMessage,
                'billCustomerName' => $billCustomerName,
                'meterNo' => $request->meter_no,
                'amount' => $request->amount,
                'paymentMethod' => $request->payment_method,
                'phoneNumber' => $phone_number,
                'is_self' => $request->is_self,
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

    public function zesco_token(Request $request)
    {
        // Retrieve the amount and meter number from the request
        $request->validate([
            'meter_no' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'is_self' => 'required'
        ]);


        $ref_no = $this->generatePaymentReferenceNumber();
        $amount = $request->amount;
        $meter_no = $request->meter_no;
        $payment_method = $request->payment_method;
        $payment_channel = $request->payment_channel;
        $phone_number = $request->phone_number;
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
                $this->primeNetZescoPayment($phone_number, $amount, $ref_no);

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
                    "meter_no" => $meter_no
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
            //Process geepay
            $service_fee = 2;
            //First check the balance of the account for the customer
            if (ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance >= ($amount + $service_fee)) {
                //get payer current balance
                $payer_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;

                //save payment intent
                $zesco_payment_intent = ConsumerTransaction::create([
                    "consumer_id" => Auth::user()->id,
                    "consumer_name" => Auth::user()->name,
                    "phone_number" => Auth::user()->phone_number,
                    "payment_reference_number" => $ref_no,
                    "channel" => $payment_method,
                    "method" => $payment_method,
                    "type" => "ZESCO Bill",
                    "partner" => "third-party",
                    "amount" => $amount,
                    "status" => 1, //pending
                    "custom_message" => "ZESCO units"
                ]);

                $zesco_payment_intent->save();

                //change the current balance
                $new_current_balance = $payer_current_balance - $amount;

                $update_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->update([
                    "balance" => $new_current_balance
                ]);

                //send data to Konse Konse to process ZESCO purchase
                return $this->process_konse_konse_zesco_purchase($meter_no, $ref_no, $total_amount, $request->is_self, $request->phone_number,$convenience_fee, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge,$new_current_balance);

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

    function process_konse_konse_zesco_purchase($meter_no, $ref_no, $amount, $is_self, $phone_number,$convenience_fee, $cgrate_percentage, $geepay_percentage, $cgrate_fixed_charge, $geepay_fixed_charge,$new_current_balance)
    {

        //credentials
        $konse_konse_url = env("KONSE_KONSE_URL");
        $konse_konse_username = env("KONSE_KONSE_USERNAME");
        $konse_konse_password = env("KONSE_KONSE_PASSWORD");

        //convert bill amount
        $bill = $amount * 100;

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
            <ns2:purchaseZescoVoucher xmlns:ns2="http://konik.cgrate.com">
            <Voucher>
                <distributionChannel/>
                <isFixed>false</isFixed>
                <receipient>' . $meter_no . '</receipient>
                <serviceProvider>Zesco</serviceProvider>
                <transactionReference>' . $ref_no . '</transactionReference>
                <voucherType>Token</voucherType>
                <voucherValue>' . $bill . '</voucherValue>
            </Voucher>
                </ns2:purchaseZescoVoucher>
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

            curl_close($curl);

            $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

            $xml->registerXPathNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('ns2', 'http://konik.cgrate.com');

            $responseCode = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/responseCode')[0];
            $responseMessage = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/responseMessage')[0];

            if ($responseCode == '0') {
                $purchaseId = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/purchaseId')[0];
                $voucherPinNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/voucherPinNumber')[0];
                $voucherSerialNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/voucherSerialNumber')[0];
                $additionalPinsCount = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/additionalPinsCount')[0];
                $customerAddress = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/customerAddress')[0];
                $customerAccountNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/customerAccountNumber')[0];
                $customerName = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/customerName')[0];
                $debtBalBfwd = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/debtBalBfwd')[0];
                $debtBalance = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/debtBalance')[0];
                $debtBalanceAmount = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/debtBalanceAmount')[0];
                $elecSerial = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/elecSerial')[0];
                $exciseDuty = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/exciseDuty')[0];
                $fixedCharge = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/fixedCharge')[0];
                $receiptNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/receiptNumber')[0];
                $refund = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/refund')[0];
                $serviceNumber = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/serviceNumber')[0];
                $tariff = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/tariff')[0];
                $tariffIndex = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/tariffIndex')[0];
                $totalVAT = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/totalVAT')[0];
                $tvLicence = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/tvLicence')[0];
                $units = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/units')[0];
                $kWhAmount = (string)$xml->xpath('//env:Envelope/env:Body/ns2:purchaseZescoVoucherResponse/return/zescoParameters/kWhAmount')[0];

                if($is_self == 1 || $is_self == "1")
                {
                    //send sms notification with meter no, amount, token and KWhAmount
                    $url_encoded_message = urlencode("Your have bought ZESCO Units of ZMW" . $amount . " for " . $units . " units on meter no. " . $meter_no . ". Your token is " . $voucherPinNumber . " . Txn: " . $ref_no);

                    $sendSMS = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . Auth::user()->phone_number . '&api_key=121231313213123123');

                }else{
                    //send sms notification with meter no, amount, token and KWhAmount
                    $url_encoded_message_to_other = urlencode(Auth::user()->name." has bought you ZESCO Units of ZMW" . $amount . " for " . $units . " units on meter no. " . $meter_no . ". Your token is " . $voucherPinNumber . " . Txn: " . $ref_no);

                    $sendSMSOther = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message_to_other . '.+&shortcode=2343&sender_id=GeePay&phone=' . $phone_number . '&api_key=121231313213123123');

                }

                //update the consumer transaction meta data column
                $meta_data = [
                    'code' => $responseCode,
                    'responseMessage' => $responseMessage,
                    'purchaseId' => $purchaseId,
                    'token' => $voucherPinNumber,
                    'voucherSerialNumber' => $voucherSerialNumber,
                    'additionalPinsCount' => $additionalPinsCount,
                    'customerAddress' => $customerAddress,
                    'customerAccountNumber' => $customerAccountNumber,
                    'customerName' => $customerName,
                    'debtBalBfwd' => $debtBalBfwd,
                    'debtBalance' => $debtBalance,
                    'debtBalanceAmount' => $debtBalanceAmount,
                    'elecSerial' => $elecSerial,
                    'exciseDuty' => $exciseDuty,
                    'fixedCharge' => $fixedCharge,
                    'receiptNumber' => $receiptNumber,
                    'refund' => $refund,
                    'serviceNumber' => $serviceNumber,
                    'tariff' => $tariff,
                    'tariffIndex' => $tariffIndex,
                    'totalVAT' => $totalVAT,
                    'tvLicence' => $tvLicence,
                    'units' => $units,
                    'kWhAmount' => $kWhAmount,
                    'ref_no' => $ref_no,
                    'is_self' => $is_self
                ];

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

                //updating the database
                ConsumerTransaction::where('payment_reference_number', $ref_no)->where('status', 1)->update([
                    "status" => 2,
                    "meta_data" => json_encode($meta_data)
                ]);

                //balance update
                $old_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;

                $new_balance = floatval($old_balance) - floatval($amount);

                $update_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->update([
                    "balance" => $new_balance
                ]);

                //custom response
                $custom_response = [
                    'success' => true,
                    'message' => "ZESCO Units bought successfully",
                    'ref_no' => $ref_no,
                    'kWhAmount' => $kWhAmount,
                    "token" => $voucherPinNumber,
                    'customerName' => $customerName,
                    'customerAddress' => $customerAddress,
                    'receiptNumber' => $receiptNumber,
                    'units' => $units,
                    'totalVAT' => $totalVAT,
                    'meter_no' => $meter_no,
                    'amount' => $amount,
                    'phone_number' => $phone_number,
                    'is_self' => $is_self
                ];

                return response()->json($custom_response, 200);

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
            //updating the database
            ConsumerTransaction::where('payment_reference_number', $ref_no)->where('status', 1)->update([
                "status" => 0,
                "meta_data" => "You have insufficient history to perform this transaction"
            ]);

            //the transaction was already peocessed
            $custom_response = [
                "success" => false,
                "message" => "You have insufficient history to perform this transaction"
            ];

            return response()->json($custom_response, 400);
        }
    }

    function primeNetZescoPayment($phone_number, $bill, $ref_no): void
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
