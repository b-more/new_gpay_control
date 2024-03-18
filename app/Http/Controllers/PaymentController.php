<?php

namespace App\Http\Controllers;

use App\Jobs\BackgroundMNOProcessing;
use App\Jobs\NotificationsMail;
use App\Mail\AuthActivityMail;
use App\Mail\PaymentConfirmationMail;
use App\Models\AccumulativeBalance;
use App\Models\AcumulativeBalance;
use App\Models\APICredential;
use App\Models\Business;
use App\Models\Commission;
use App\Models\CommissionReceived;
use App\Models\Consumer;
use App\Models\ConsumerBalance;
use App\Models\ConsumerCommission;
use App\Models\ConsumerTransaction;
use App\Models\CurrentBalance;
use App\Models\Customer;
use App\Models\Client;
use App\Models\FrontEndUrl;
use App\Models\Payment;
use App\Models\PaymentCommission;
use App\Models\Payout;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Url\Url;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    function generate_short_url()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $res = "";
        for ($i = 0; $i < 5; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        // Check if the short url already exists in the database
        if (DB::table('payments')->where('short_url', $res)->exists()) {
            // If the short url already exists, generate a new one recursively
            return $this->generate_short_url();
        }

        return $res;
    }

    function generatePaymentReferenceNumber() {
        $prefix = 'Ref'; // Prefix for the account number
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

    public function client_api_payment(Request $request)
    {
        //validate data sent
        $request->validate([
            "secret_id" => "required",
            "access_token" => "required",
            "txn_number" => "required",
            "amount" => "required",
            "payment_method" => "required",
            "phone_number" => "required",
            "description" => "required"
        ]);


        if(APICredential::where('secret_id', $request->secret_id)->count() > 0)
        {
            Log::info("API Credentials exist");
            //get client credentials
            $client_data = APICredential::where('secret_id', $request->secret_id)->first();
            $business = Business::where('id', $client_data->business_id)->first();
            //check if access token is correct
            if(Hash::check($request->access_token, $client_data->access_token))
            {
                Log::info("API Credentials correct");
                //configure payment channel
                $phone_number = substr($request->phone_number, -9);
                $payment_channel = "Airtel Money";
                $ref_number = $this->generatePaymentReferenceNumber();

                if(str_starts_with($phone_number, '96') || str_starts_with($phone_number, '76'))
                {
                    $payment_channel = "MTN Money";
                }elseif(str_starts_with($phone_number, '95') || str_starts_with($phone_number, '75'))
                {
                    $payment_channel = "Zamtel Money";
                }

                //proceed with payment
                if($request->payment_method == "mm")
                {
                    $charge_details = PaymentCommission::where("category", "collections")->first();

                    //add Cgrate + GeePay percentage, then mu
                    $convenience_fee = (((floatval($charge_details->cgrate_percentage) + floatval($charge_details->geepay_percentage))/100) * floatval($request->amount)) + (floatval($charge_details->cgrate_fixed_charge) + floatval($charge_details->geepay_fixed_charge));

                    $total_amount = floatval($request->amount) + $convenience_fee;

                    $payment_intent = new Payment;

                    $payment_intent->business_id = $business->id;
                    $payment_intent->payment_method_id = 1;
                    $payment_intent->payment_channel = $payment_channel;
                    $payment_intent->business_name = $business->business_name;
                    $payment_intent->account_number = $business->account_number;
                    $payment_intent->payment_reference_number = $ref_number;
                    $payment_intent->txn_number = "pending";
                    $payment_intent->phone_number = $phone_number;
                    $payment_intent->description = $request->description;
                    $payment_intent->received_amount = $total_amount;
                    $payment_intent->commission_charged = $convenience_fee;
                    $payment_intent->payout_amount = $request->amount;
                    $payment_intent->status = 1; //pending

                    $payment_intent->save();

                    //save commission fees
                    //define definite commission for each
                    $cgrate_percentage = (floatval($charge_details->cgrate_percentage)/100) * floatval($request->amount);
                    $geepay_percentage = (floatval($charge_details->geepay_percentage)/100) * floatval($request->amount);
                    $cgrate_fixed_charge = floatval($charge_details->cgrate_fixed_charge);
                    $geepay_fixed_charge = floatval($charge_details->geepay_fixed_charge);

                    // save the commissions
                    $new_comission_record = Commission::create([
                        "business_id" => $business->id,
                        "transaction_reference_number" => $ref_number,
                        "cgrate_percentage" => $cgrate_percentage,
                        "geepay_percentage" => $geepay_percentage,
                        "cgrate_fixed_charge" => $cgrate_fixed_charge,
                        "geepay_fixed_charge" => $geepay_fixed_charge
                    ]);

                    $new_comission_record->save();

                    //provok Cgrate for prompt


                    //save customer data
                    if(Customer::where('phone_number', $phone_number)->count() > 0)
                    {
                        $already_saved = "already saved";
                    }else{
                        if($request->customer_name == "name")
                        {
                            $new_customer = Customer::create([
                                "business_id" => $business->id,
                                "name" => "Anonymous",
                                "phone_number" => $phone_number
                            ]);

                            $new_customer->save();

                        }else{
                            $new_customer = Customer::create([
                                "business_id" => $business->id,
                                "name" => $request->customer_name,
                                "phone_number" => $phone_number
                            ]);

                            $new_customer->save();
                        }

                    }

                    return $this->konse_konse_send_mobile_money($total_amount,"0".$phone_number,$ref_number,$payment_channel, $business, $payment_intent->id,$request->amount);

                }elseif($request->payment_method == "vm")
                {
                    //VISA and MASTERCARD payment logic
                    $new_payment = Payment::create([

                    ]);

                    $new_payment->save();

                    //send confirmation email to the business client
                }
            }else{
                //if wrong access token
                $custom_response = [
                    "success" => false,
                    "error_code" => "101",
                    "message" => "Wrong credentials provided"
                ];

                return response()->json($custom_response, 403);
            }

        }else{
            //wrong credentials
            $custom_response = [
                "success" => false,
                "error_code" => "102",
                "message" => "Wrong credentials provided"
            ];

            return response()->json($custom_response, 400);

        }

    }

    function konse_konse_send_mobile_money($amount, $phone_number, $ref_number, $mno, $business, $payment_id, $payout_amount)
    {
        Log::info("MNO send via Konse Konse to phone: ".$phone_number." Amount: ".$amount);
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
                <kon:processCustomerPayment>
                    <transactionAmount>'.$amount.'</transactionAmount>
                    <customerMobile>'.$phone_number.'</customerMobile>
                    <paymentReference>'.$ref_number.'</paymentReference>
                </kon:processCustomerPayment>
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

        $responseCode = (string)$xml->xpath('//env:Envelope/env:Body/ns2:processCustomerPaymentResponse/return/responseCode')[0];
        $responseMessage = (string)$xml->xpath('//env:Envelope/env:Body/ns2:processCustomerPaymentResponse/return/responseMessage')[0];

        if ($responseCode == '0' || $responseCode == 0)
        {
            $short_url_code = $this->generate_short_url();

            //get frontend url
            $frontend_url = FrontEndUrl::first()->domain;

            //the shortened url
            $receipt_url = $frontend_url . '/r/' . $short_url_code;
            //success
            $paymentID = (string)$xml->xpath('//env:Envelope/env:Body/ns2:processCustomerPaymentResponse/return/paymentID')[0];

            $message = "Your payment of ".number_format($amount,2)." to ".$business->business_name." has been successful. Txn:".$paymentID.".Download your receipt on ".$receipt_url;

            //update the payment record
            $update_payment = Payment::where('id', $payment_id)->update([
                "txn_number" => $paymentID,
                "short_url" => $receipt_url,
                "status" => 2 //successful
            ]);

            //save and update the payout resource
            if(Payout::where('business_id', $business->id)->count() > 0)
            {
                //payout record exist
                $old_balance = Payout::where('business_id', $business->id)->first()->new_balance;
                $new_balance = (floatval($old_balance) + floatval($payout_amount));
                $update_payout_balance = Payout::where('business_id', $business->id)->update([
                    "old_balance" => $old_balance,
                    "new_balance" => $new_balance
                ]);
            }else{
                $new_payout_balance = Payout::create([
                    "business_id" => $business->id,
                    "account_number" => $business->account_number,
                    "business_name" => $business->business_name,
                    "old_balance" => "0",
                    "new_balance" => $payout_amount
                ]);

                $new_payout_balance->save();
            }

            //send receiver sms notification
            $this->sendOntechSmsNotification($message, $phone_number);

            //send app notification
            Notification::make()
                ->title('Transaction successful')
                ->success()
                ->body('Collected a payment of ZMW'.$payout_amount)
                ->sendToDatabase(Client::where('business_id', $business->id)->get())
                ->send();


            $custom_response = [
                "success" => true,
                "message" => "transaction successful",
                "txn_number" => $paymentID,
                "ref_number" => $ref_number,
                "short_url" => $receipt_url,
                "date" => now()
            ];

            return response()->json($custom_response, 200);

        }elseif ($responseCode == '702' || $responseCode == 702)
        {
            //Customer not found
            //update the payment record
            $update_payment = Payment::where('id', $payment_id)->update([
                "status" => 0 //failed
            ]);

            $custom_response = [
                "success" => false,
                "message" => "transaction failed, customer not found",
                "ref_number" => $ref_number,
                "date" => now()
            ];

            return response()->json($custom_response, 400);

        }elseif($responseCode == '104'  || $responseCode == 104)
        {
            //Insufficient credit
            $update_payment = Payment::where('id', $payment_id)->update([
                "status" => 0 //failed
            ]);

            $custom_response = [
                "success" => false,
                "message" => "transaction failed, insufficient credit",
                "ref_number" => $ref_number,
                "date" => now()
            ];

            return response()->json($custom_response, 400);

        }

    }

    function sendOntechSmsNotification(string $message, string $phone_number): void
    {
        // Send confirmation SMS
        $url_encoded_message = urlencode($message);

        $url = 'https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $phone_number . '&api_key=121231313213123123';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Use this only if you have SSL verification issues
        $response = curl_exec($ch);
        curl_close($ch);
    }

    function checkNetwork($phone_number)
    {
        $payment_channel = "Unknown";

        if(str_starts_with($phone_number, '96') || str_starts_with($phone_number, '76'))
        {
            $payment_channel = "MTN Money";
        }elseif(str_starts_with($phone_number, '95') || str_starts_with($phone_number, '75'))
        {
            $payment_channel = "Zamtel Money";
        }elseif(str_starts_with($phone_number, '97') || str_starts_with($phone_number, '77'))
        {
            $payment_channel = "Airtel Money";
        }

        return $payment_channel;
    }

    public function business_payments(Request $request)
    {
        $payments = Payment::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $payments->where('id', 'like', "%$search%")
                ->orWhere('payment_reference_number', 'like', "%$search%")
                ->orWhere('payment_channel', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('received_amount', 'like', "%$search%")
                ->orWhere('created_at', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        }

        $pageSize = $request->input('pageSize', 10);
        $payments = $payments->where('status', 2)->orderBy('updated_at','DESC')->paginate($pageSize);

        return $payments->withHeaders([
            'Access-Control-Allow-Origin' => 'https://geepaybiz.ontechcloud.tech',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With'
        ]);
    }

    public function all_payments_api(Request $request)
    {
        $payments = Payment::where('business_id', $request->input('nob'))->where('is_deleted', 0);

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $payments = Payment::where('business_id', $request->input('nob'))->where('is_deleted', 0)->where('id', 'like', "%$search%")
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

    public function successful_payments_api(Request $request)
    {
        $payments = Payment::where('business_id', $request->input('nob'))->where('status', 2)->where('is_deleted', 0)->where('is_refunded',0);

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $payments = Payment::where('business_id', $request->input('nob'))->where('status', 2)->where('is_deleted', 0)->where('is_refunded',0)->where('id', 'like', "%$search%")
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

    public function pending_payments_api(Request $request)
    {
        $payments = Payment::where('business_id', $request->input('nob'))->where('status', 1)->where('is_deleted', 0);

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $payments = Payment::where('business_id', $request->input('nob'))->where('status', 1)->where('is_deleted', 0)->where('id', 'like', "%$search%")
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

    public function failed_payments_api(Request $request)
    {
        $payments = Payment::where('business_id', $request->input('nob'))->where('status', 0)->where('is_deleted', 0);

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $payments = Payment::where('business_id', $request->input('nob'))->where('status', 0)->where('is_deleted', 0)->where('id', 'like', "%$search%")
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

    public function refunded_payments_api(Request $request)
    {
        $payments = Payment::where('business_id', $request->input('nob'))->where('is_deleted', 0)->where('is_refunded',1);

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $payments = Payment::where('business_id', $request->input('nob'))->where('is_deleted', 0)->where('is_refunded',1)->where('id', 'like', "%$search%")
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

    public function instant_payment(Request $request){
        $request->validate([
            "owner_id" => "required",
            "amount" => "required",
            "description" => "required",
            "phone_number" => "required"
        ]);

        if(Business::where('user_id', $request->owner_id)->count() > 0)
        {
            //get business details
            $business = Business::where('user_id', $request->owner_id)->first();

            $payment_method = "mm";
            //configure payment channel
            $phone_number = substr($request->phone_number, -9);
            $payment_channel = "Airtel Money";
            //$ref_number = Str::uuid()->toString();
            $ref_number = $this->generatePaymentReferenceNumber();

            if(str_starts_with($phone_number, '96') || str_starts_with($phone_number, '76'))
            {
                $payment_channel = "MTN Money";
            }elseif(str_starts_with($phone_number, '95') || str_starts_with($phone_number, '75'))
            {
                $payment_channel = "Zamtel Money";
            }

            //proceed with payment
            if($payment_method == "mm")
            {
                //calculate commission
                $commission_percentage = PaymentCommission::where('category','collections')->first()->calculations;

                //calculate GeePay commission value
                $commission_value = ($commission_percentage*$request->amount);

                //calculate payout amount
                $payout_amount = $request->amount - $commission_value;

                //save commission
                $new_commission = CommissionReceived::create([
                    "business_id" => $business->id,
                    "payment_reference_number" => $ref_number,
                    "amount" => $commission_value
                ]);

                $new_commission->save();

                /*//update accumulative balance
                $current_accumulative_balance = AcumulativeBalance::where('business_id', $business->id)->first()->payments;

                $updated_accumulative_balance = $current_accumulative_balance + $request->amount;

                //update accumulative database record
                $update_accumulative_payment = AcumulativeBalance::where('business_id', $business->id)->update([
                    "payments" => $updated_accumulative_balance
                ]);*/

                $paying_customer="";

                //check contact
                if(Customer::where('phone_number', '260'.$phone_number)->count()>0)
                {
                    //create new customer details
                    $paying_customer = Customer::where('phone_number', '260'.$phone_number)->first();
                }else{
                    //create new record
                    $new_customer = Customer::create([
                        "business_id" => $business->id,
                        "name" => $request->customer_name,
                        "phone_number" => '260'.$phone_number
                    ]);

                    $new_customer->save();

                    if($new_customer){
                        $paying_customer = Customer::where('id', $new_customer->id)->first();
                    }

                }

                //create new short url code
                $short_url_code = $this->generate_short_url();

                //initiate MNO Payment Intent
                if($payment_channel == "Airtel Money") {
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

                    $reference_statement = substr("payment to ".$business->business_name, 0, 24);

                    if($token) {
                        $body = [];
                        $body['reference'] = $reference_statement;
                        $body['subscriber']['country'] = "ZM";
                        $body['subscriber']['currency'] = "ZMW";
                        $body['subscriber']['msisdn'] = $phone_number;
                        $body['transaction']['amount'] = $request->amount;
                        $body['transaction']['country'] = "ZM";
                        $body['transaction']['currency'] = "ZMW";
                        $body['transaction']['id'] = $ref_number;


                        $headers = [];
                        $headers[] = "X-Country: ZM";
                        $headers[] = "X-Currency: ZMW";
                        $headers[] = "Authorization: Bearer " .$token;
                        $headers[] = "Content-Type: application/json";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://openapi.airtel.africa/merchant/v1/payments/");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

                        $curl_info = curl_getinfo($ch);

                        $result = curl_exec($ch);
                        curl_close($ch);

                    }

                }elseif ($payment_channel == "MTN Money"){
                    $payment_channel = "MTN Money";
                }elseif ($payment_channel == "Zamtel Money"){
                    $payment_channel = "Zamtel Money";
                }

                //save payment
                $new_payment = Payment::create([
                    "business_id" => $business->id,
                    "payment_method_id" => 1,
                    "customer_id" => $paying_customer->id,
                    "payment_channel" => $payment_channel,
                    "business_name" => $business->business_name,
                    "account_number" => $business->account_number,
                    "payment_reference_number" => $ref_number,
                    "txn_number" => $ref_number,
                    "phone_number" => "260".$phone_number,
                    "description" => $request->description,
                    "received_amount" => $request->amount,
                    "commission_charged" => $commission_value,
                    "payout_amount" => $payout_amount,
                    "short_url" => $short_url_code
                ]);

                $new_payment->save();

                //get frontend url
                $frontend_url = FrontEndUrl::first()->domain;

                //the shortened url
                $receipt_url = $frontend_url . '/r/' .$short_url_code;

                //send to background job for 3 factor transaction confirmation
                BackgroundMNOProcessing::dispatch($receipt_url, $business->business_email, $business->business_name, $request->amount,$phone_number, $ref_number, $payment_channel);

                $custom_response = [
                    "success" => true,
                    "message" => "transaction initiated successful",
                    "payment_id" => $new_payment->id,
                    "receipt_url" => $receipt_url,
                    "business_email" => $business->business_email,
                    "business_name" => $business->business_name,
                    "amount" => $request->amount,
                    "phone_number" => $phone_number,
                    "ref_number" => $ref_number,
                    "payment_channel" => $payment_channel,
                    "date" => now()
                ];

                return response()->json($custom_response, 200);

            }elseif($request->payment_method == "vm")
            {
                //VISA and MASTERCARD payment logic
                $new_payment = Payment::create([

                ]);

                $new_payment->save();

                //send confirmation email to the business clien
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "no business record",
                "date" => now()
            ];

            return response()->json($custom_response, 400);
        }

    }

    public function instant_payment_pos(Request $request){
        $request->validate([
            "amount" => "required",
            "phone_number" => "required"
        ]);

        if(Business::where('user_id', Auth::user()->id)->count() > 0)
        {
            //get business details
            $business = Business::where('user_id', Auth::user()->id)->first();

            $payment_method = "mm";
            //configure payment channel
            $phone_number = substr($request->phone_number, -9);
            $payment_channel = "Airtel Money";
            //$ref_number = Str::uuid()->toString();
            $ref_number = $this->generatePaymentReferenceNumber();

            if(str_starts_with($phone_number, '96') || str_starts_with($phone_number, '76'))
            {
                $payment_channel = "MTN Money";
            }elseif(str_starts_with($phone_number, '95') || str_starts_with($phone_number, '75'))
            {
                $payment_channel = "Zamtel Money";
            }

            //proceed with payment
            if($payment_method == "mm")
            {
                //calculate commission
                $commission_percentage = PaymentCommission::where('category','collections')->first()->calculations;

                //calculate GeePay commission value
                $commission_value = ($commission_percentage*$request->amount);

                //calculate payout amount
                $payout_amount = $request->amount - $commission_value;

                //save commission
                $new_commission = CommissionReceived::create([
                    "business_id" => $business->id,
                    "payment_reference_number" => $ref_number,
                    "amount" => $commission_value
                ]);

                $new_commission->save();

                /*//update accumulative balance
                $current_accumulative_balance = AcumulativeBalance::where('business_id', $business->id)->first()->payments;

                $updated_accumulative_balance = $current_accumulative_balance + $request->amount;

                //update accumulative database record
                $update_accumulative_payment = AcumulativeBalance::where('business_id', $business->id)->update([
                    "payments" => $updated_accumulative_balance
                ]);*/

                $paying_customer="";

                //check contact
                if(Customer::where('phone_number', '260'.$phone_number)->count()>0)
                {
                    //create new customer details
                    $paying_customer = Customer::where('phone_number', '260'.$phone_number)->first();
                }else{
                    //create new record
                    $new_customer = Customer::create([
                        "business_id" => $business->id,
                        "name" => $request->customer_name,
                        "phone_number" => '260'.$phone_number
                    ]);

                    $new_customer->save();

                    if($new_customer){
                        $paying_customer = Customer::where('id', $new_customer->id)->first();
                    }

                }

                //create new short url code
                $short_url_code = $this->generate_short_url();

                //initiate MNO Payment Intent
                if($payment_channel == "Airtel Money") {
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

                    $reference_statement = substr("payment to ".$business->business_name, 0, 24);

                    if($token) {
                        $body = [];
                        $body['reference'] = $reference_statement;
                        $body['subscriber']['country'] = "ZM";
                        $body['subscriber']['currency'] = "ZMW";
                        $body['subscriber']['msisdn'] = $phone_number;
                        $body['transaction']['amount'] = $request->amount;
                        $body['transaction']['country'] = "ZM";
                        $body['transaction']['currency'] = "ZMW";
                        $body['transaction']['id'] = $ref_number;


                        $headers = [];
                        $headers[] = "X-Country: ZM";
                        $headers[] = "X-Currency: ZMW";
                        $headers[] = "Authorization: Bearer " .$token;
                        $headers[] = "Content-Type: application/json";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://openapi.airtel.africa/merchant/v1/payments/");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

                        $curl_info = curl_getinfo($ch);

                        $result = curl_exec($ch);
                        curl_close($ch);

                    }

                }elseif ($payment_channel == "MTN Money"){
                    $payment_channel = "MTN Money";
                }elseif ($payment_channel == "Zamtel Money"){
                    $payment_channel = "Zamtel Money";
                }

                //save payment
                $new_payment = Payment::create([
                    "business_id" => $business->id,
                    "payment_method_id" => 1,
                    "customer_id" => $paying_customer->id,
                    "payment_channel" => $payment_channel,
                    "business_name" => $business->business_name,
                    "account_number" => $business->account_number,
                    "payment_reference_number" => $ref_number,
                    "txn_number" => $ref_number,
                    "phone_number" => "260".$phone_number,
                    "description" => "POS Payment",
                    "received_amount" => $request->amount,
                    "commission_charged" => $commission_value,
                    "payout_amount" => $payout_amount,
                    "short_url" => $short_url_code
                ]);

                $new_payment->save();

                //get frontend url
                $frontend_url = FrontEndUrl::first()->domain;

                //the shortened url
                $receipt_url = $frontend_url . '/r/' .$short_url_code;

                //send to background job for 3 factor transaction confirmation
                BackgroundMNOProcessing::dispatch($receipt_url, $business->business_email, $business->business_name, $request->amount,$phone_number, $ref_number, $payment_channel);

                $custom_response = [
                    "success" => true,
                    "message" => "transaction initiated successful",
                    "payment_id" => $new_payment->id,
                    "receipt_url" => $receipt_url,
                    "business_email" => $business->business_email,
                    "business_name" => $business->business_name,
                    "amount" => $request->amount,
                    "phone_number" => $phone_number,
                    "ref_number" => $ref_number,
                    "payment_channel" => $payment_channel,
                    "date" => now()
                ];

                return response()->json($custom_response, 200);

            }elseif($request->payment_method == "vm")
            {
                //VISA and MASTERCARD payment logic
                $new_payment = Payment::create([

                ]);

                $new_payment->save();

                //send confirmation email to the business clien
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "no business record",
                "date" => now()
            ];

            return response()->json($custom_response, 400);
        }

    }

    public function instant_payment_confirmation_manual(Request $request)
    {
        $payment_to_query = Payment::where('id', $request->id)->first();
        //get the record to query
        if(Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->count() > 0) {
            //Airtel Money payment confirmation
            if ($payment_to_query->payment_channel == "Airtel Money") {
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

                    //check payment confirmation

                    $payment_response = Http::withHeaders([
                        'X-Currency' => 'ZMW',
                        'X-Country' => 'ZM',
                        'Accept' => '*/*',
                        'Authorization' => 'Bearer ' . $token
                    ])->get('https://openapi.airtel.africa/standard/v1/payments/' . $payment_to_query->payment_reference_number);

                    $status_state = $payment_response->status();
                    $status_json = $payment_response->json();

                    if ($status_state == 200) {
                        //check if the key data exist
                        if (array_key_exists("data", $status_json)) {
                            if ($status_json['data']['transaction']['status'] == "TS") {
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 2 //successfull
                                ]);

                                //get current balance
                                $old_current_payment_balance = CurrentBalance::where('business_id',$payment_to_query->business_id)->first()->payments;

                                //get current balance
                                $old_accumulated_balance = AcumulativeBalance::where('business_id',$payment_to_query->business_id)->first()->payments;

                                //current balance transaction calculations
                                $new_current_balance = $old_current_payment_balance + $payment_to_query->received_amount;

                                //current balance transaction calculations
                                $new_accumulated_balance = $old_accumulated_balance + $payment_to_query->received_amount;

                                $update_payments_balance = CurrentBalance::where('business_id',$payment_to_query->business_id)->update([
                                    "payments" => $new_current_balance
                                ]);

                                $update_accumulated_balance = AcumulativeBalance::where('business_id',$payment_to_query->business_id)->update([
                                    "payments" => $new_accumulated_balance
                                ]);

                                //send out notifications
                                $business_contact = Business::where('id', $payment_to_query->business_id)->first();
                                //send confirmation email to the business client


                                //get frontend url
                                $frontend_url = FrontEndUrl::first()->domain;
                                //receipt url
                                $receipt_url = $frontend_url.'/r/'.$payment_to_query->short_url;

                                //send confirmation sms
                                $url_encoded_message = urlencode("Your payment of ZMW" . $payment_to_query->received_amount . " to " . $business_contact->business_name . " is successful. Download your receipt at: " . $receipt_url);

                                $sendSMS = Http::withoutVerifying()
                                    ->post('http://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $payment_to_query->phone_number . '&api_key=121231313213123123');

                                /*$sendZamtelAPI = Http::withoutVerifying()
                                    ->post('https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/38050146d5e214e9731bc939e9668c4e/contacts/'.$payment_to_query->phone_number.'/senderId/GeePay/message/'.$url_encoded_message);*/



                                $custom_response = [
                                    "success" => true,
                                    "message" => "transaction processed successfully",
                                    "phone_number" => $payment_to_query->phone_number,
                                    "reference_number" => $payment_to_query->payment_reference_number,
                                    "amount" => $payment_to_query->received_amount,
                                    "initiated_at" => $payment_to_query->created_at->format('H:i:s'),
                                    "confirmed_at" => $payment_to_query->updated_at->format('H:i:s'),
                                    "payment_channel" => $payment_to_query->payment_channel,
                                    "date" => now()->toDateString()
                                ];

                                return response()->json($custom_response, 200);

                            } else if ($status_json['data']['transaction']['status'] == "TIP") {
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 0 //failed
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "Transaction not confirmed"
                                ];

                                return response()->json($custom_response, 400);
                            }else{
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 0 //failed
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "transaction invalid"
                                ];

                                return response()->json($custom_response, 400);
                            }

                        } else {

                            //payment still in progress
                            $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                'status' => 0 //failed
                            ]);

                            $custom_response = [
                                "success" => false,
                                "message" => "transaction failed"
                            ];

                            return response()->json($custom_response, 400);
                        }
                    }else{
                        $custom_response = [
                            "success" => false,
                            "message" => "try again later"
                        ];

                        return response()->json($custom_response, 400);
                    }
                }
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "this transaction is not pending"
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function qr_code_pay_pos(Request $request)
    {
        $request->validate([
            'qr_code'=> 'required'
        ]);

        if(Consumer::where('qr_code',$request->qr_code)->count()>0){
            $payer_details = Consumer::where('qr_code',$request->qr_code)->first();

            $ref_number = $this->generatePaymentReferenceNumber();

            if(ConsumerBalance::where('consumer_id',$payer_details->id)->first()->balance >= $request->amount){
                $business = Business::where('user_id', Auth::user()->id)->first();

                $payer_current_balance = ConsumerBalance::where('consumer_id',$payer_details->id)->first()->balance;

                //calculate commission
                $charge_details = PaymentCommission::where("category", "wallet to business")->first();

                $convenience_fee = (((floatval($charge_details->cgrate_percentage) + floatval($charge_details->geepay_percentage))/100) * floatval($request->amount)) + (floatval($charge_details->cgrate_fixed_charge) + floatval($charge_details->geepay_fixed_charge));

                $total_amount = floatval($request->amount) + $convenience_fee;

                //commission calculations
                $cgrate_percentage = (floatval($charge_details->cgrate_percentage)/100) * floatval($request->amount);
                $geepay_percentage = (floatval($charge_details->geepay_percentage)/100) * floatval($request->amount);
                $cgrate_fixed_charge = $charge_details->cgrate_fixed_charge;
                $geepay_fixed_charge = $charge_details->geepay_fixed_charge;

                if(floatval($payer_current_balance) > $total_amount)
                {
                    $short_url_code = $this->generate_short_url();

                    //save payment
                    $new_payment = Payment::create([
                        "business_id" => $business->id,
                        "payment_method_id" => 1,
                        "customer_id" => $payer_details->id,
                        "payment_channel" => "GeePay",
                        "business_name" => $business->business_name,
                        "account_number" => $business->account_number,
                        "payment_reference_number" => $ref_number,
                        "txn_number" => $ref_number,
                        "phone_number" => $payer_details->phone_number,
                        "description" => "wallet to business",
                        "received_amount" => $total_amount,
                        "commission_charged" => $convenience_fee,
                        "payout_amount" => $request->amount,
                        "short_url" => $short_url_code,
                        "status" => 2
                    ]);

                    $new_payment->save();

                    //get frontend url
                    $frontend_url = FrontEndUrl::first()->domain;

                    //the shortened url
                    $receipt_url = $frontend_url . '/r/' .$short_url_code;

                    //save consumer transaction record
                    //set sender current balance
                    $new_sender_balance = ($payer_current_balance - $request->amount);

                    //sender transaction details
                    $payer_transaction_details = ConsumerTransaction::create([
                        "consumer_id" => $payer_details->id,
                        "consumer_name" => $payer_details->name,
                        "payment_reference_number" => $ref_number,
                        "type" => "sent", //send,deposit,transfer,pay
                        "partner" => $business->id,//phone number, business_id, Agent_id, third-party name
                        "partner_type" => "Business POS",//Wallet, Business,Agent,third-party name
                        "amount" => $total_amount,
                        "status" => 2, //success
                        "channel" => "GeePay",
                        "custom_message"=>"wallet to business",
                        "phone_number"=> $payer_details->phone_number
                    ]);

                    $payer_transaction_details->save();

                    //new sender balance update
                    $new_payer_balance_update = ConsumerBalance::where('consumer_id',$payer_details->id)->update([
                        "balance" => $new_sender_balance
                    ]);

                    //consumer commission
                    $consumer_commission = ConsumerCommission::create([
                        "consumer_id" => Auth::user()->id,
                        "transaction_reference_number" => $ref_number,
                        "cgrate_percentage" => $cgrate_percentage,
                        "geepay_percentage" => $geepay_percentage,
                        "cgrate_fixed_charge" => $cgrate_fixed_charge,
                        "geepay_fixed_charge" => $geepay_fixed_charge
                    ]);

                    $consumer_commission->save();

                    //record payout balance
                    if(Payout::where("business_id",$business->id)->count() > 0)
                    {
                        //add to the old balance
                        $old_balance = Payout::where("business_id",$business->id)->first()->new_balance;

                        $new_balance = floatval($old_balance) + $request->amount;

                        $update_payout_balance = Payout::where("business_id",$business->id)->update([
                            "old_balance" => $old_balance,
                            "new_balance" => $new_balance
                        ]);
                    }else{
                        //create a new payout balance
                        $new_payout_balance = Payout::create([
                            "business_id" => $business->id,
                            "business_name" => $business->business_name,
                            "account_number" => $business->account_number,
                            "old_balance" => "0",
                            "new_balance" => $request->amount
                        ]);

                        $new_payout_balance->save();
                    }

                    //Sender message body
                    $url_encoded_message = urlencode("You have sent  ZMW".$request->amount." to ".$business->business_name.".Your GeePay Balance is now ZMW".number_format($new_sender_balance,2)." Txn Id ".$ref_number);

                    //send receiver sms notification
                    $sendSenderSMS = Http::post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $payer_details->phone_number . '&api_key=121231313213123123');

                    $custom_response = [
                        "success" => true,
                        "message" => "transaction processed successfully",
                        "phone_number" => $payer_details->phone_number,
                        "reference_number" => $ref_number,
                        "amount" => $request->amount,
                        "initiated_at" => now()->toTimeString(),
                        "confirmed_at" => now()->toTimeString(),
                        "payment_channel" => "GeePay",
                        "date" => now()->toDateString()
                    ];

                    return response()->json($custom_response, 200);
                }else{
                    $custom_response = [
                        "success" => false,
                        "message" => "Customer has insufficient balance to make this transaction"
                    ];

                    return response()->json($custom_response, 400);
                }










            }else{
                $custom_response = [
                    "success" => false,
                    "message" => "Insufficient Balance"
                ];

                return response()->json($custom_response,400);
            }
        }else{
            $custom_response = [
                'success'=> false,
                'message'=> 'User not yet registered on GeePay'
            ];
            return response()->json($custom_response,404);
        }
    }

    public function instant_payment_confirmation(Request $request)
    {
        //get the record to query
        if(Payment::where('payment_reference_number', $request->ref_number)->where('status', "1")->count() > 0) {
            //Airtel Money payment confirmation
            if ($request->payment_channel == "Airtel Money") {
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

                    //check payment confirmation

                    $payment_response = Http::withHeaders([
                        'X-Currency' => 'ZMW',
                        'X-Country' => 'ZM',
                        'Accept' => '*/*',
                        'Authorization' => 'Bearer ' . $token
                    ])->get('https://openapi.airtel.africa/standard/v1/payments/' . $request->ref_number);

                    $status_state = $payment_response->status();
                    $status_json = $payment_response->json();

                    if ($status_state == 200) {
                        //check if the key data exist
                        if (array_key_exists("data", $status_json)) {
                            if ($status_json['data']['transaction']['status'] == "TS") {
                                $update_status = Payment::where('payment_reference_number', $request->ref_number)->update([
                                    'status' => "2" //successfull
                                ]);

                                //get current balance
                                $old_current_payment_balance = CurrentBalance::where('business_id',$request->business_id)->first()->payments;

                                //get current balance
                                $old_accumulated_balance = AccumulativeBalance::where('business_id',$request->business_id)->first()->payments;

                                //current balance transaction calculations
                                $new_current_balance = $old_current_payment_balance + $request->amount;

                                //current balance transaction calculations
                                $new_accumulated_balance = $old_accumulated_balance + $request->amount;

                                $update_payments_balance = CurrentBalance::where('business_id',$request->business_id)->update([
                                    "payments" => $new_current_balance
                                ]);

                                $update_accumulated_balance = AccumulativeBalance::where('business_id',$request->business_id)->update([
                                    "payments" => $new_accumulated_balance
                                ]);

                                //send out notifications

                                //send confirmation email to the business client
                                Mail::to($request->business_email)->send(new PaymentConfirmationMail($request->business_name, $request->amount, "260" . $request->phone_number, $request->ref_number,"to business"));

                                //send sms notification to business client's customer

                                //send confirmation sms
                                $url_encoded_message = urlencode("Your payment of ZMW" . $request->amount . " to " . $request->business_name . " is successful. Download your receipt at: " . $request->receipt_url);

                                $sendSMS = Http::withoutVerifying()
                                    ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=260' . $request->phone_number . '&api_key=121231313213123123');

                                /*$sendZamtelAPI = Http::withoutVerifying()
                                    ->post('https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/38050146d5e214e9731bc939e9668c4e/contacts/'.$request->phone_number.'/senderId/GeePay/message/'.$url_encoded_message);*/
                                $custom_response = [
                                    "success" => true,
                                    "message" => "transaction processed successfully",
                                    "date" => now()
                                ];

                                return response()->json($custom_response, 200);

                            } else if ($status_json['data']['transaction']['status'] == "TIP") {
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $request->ref_number)->update([
                                    'status' => "1" //pending
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "no payment failed"
                                ];

                                return response()->json($custom_response, 400);
                            }

                        } else {
                            $custom_response = [
                                "success" => false,
                                "message" => "transaction failed"
                            ];

                            return response()->json($custom_response, 400);
                        }
                    }
                }
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "this transaction is not pending"
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function instant_payment_confirmation_manual_pos(Request $request)
    {
        $payment_to_query = Payment::where('id', $request->id)->first();
        //get the record to query
        if(Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->count() > 0) {
            //Airtel Money payment confirmation
            if ($payment_to_query->payment_channel == "Airtel Money") {
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

                    //check payment confirmation

                    $payment_response = Http::withHeaders([
                        'X-Currency' => 'ZMW',
                        'X-Country' => 'ZM',
                        'Accept' => '*/*',
                        'Authorization' => 'Bearer ' . $token
                    ])->get('https://openapi.airtel.africa/standard/v1/payments/' . $payment_to_query->payment_reference_number);

                    $status_state = $payment_response->status();
                    $status_json = $payment_response->json();

                    if ($status_state == 200) {
                        //check if the key data exist
                        if (array_key_exists("data", $status_json)) {
                            if ($status_json['data']['transaction']['status'] == "TS") {
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 2 //successfull
                                ]);

                                //get current balance
                                $old_current_payment_balance = CurrentBalance::where('business_id',$payment_to_query->business_id)->first()->payments;

                                //get current balance
                                $old_accumulated_balance = AccumulativeBalance::where('business_id',$payment_to_query->business_id)->first()->payments;

                                //current balance transaction calculations
                                $new_current_balance = $old_current_payment_balance + $payment_to_query->received_amount;

                                //current balance transaction calculations
                                $new_accumulated_balance = $old_accumulated_balance + $payment_to_query->received_amount;

                                $update_payments_balance = CurrentBalance::where('business_id',$payment_to_query->business_id)->update([
                                    "payments" => $new_current_balance
                                ]);

                                $update_accumulated_balance = AccumulativeBalance::where('business_id',$payment_to_query->business_id)->update([
                                    "payments" => $new_accumulated_balance
                                ]);

                                //send out notifications
                                $business_contact = Business::where('id', $payment_to_query->business_id)->first();
                                //send confirmation email to the business client
                                Mail::to($business_contact->business_email)->send(new PaymentConfirmationMail($business_contact->business_name, $payment_to_query->received_amount, $payment_to_query->phone_number, $payment_to_query->payment_reference_number, "to business"));

                                //get frontend url
                                $frontend_url = FrontEndUrl::first()->domain;
                                //receipt url
                                $receipt_url = $frontend_url.'/'.$payment_to_query->short_url;

                                //send confirmation sms
                                $url_encoded_message = urlencode("Your payment of ZMW" . $payment_to_query->received_amount . " to " . $business_contact->business_name . " is successful. Download your receipt at: " . $receipt_url);

                                $sendSMS = Http::withoutVerifying()
                                    ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $payment_to_query->phone_number . '&api_key=121231313213123123');

                                /*$sendZamtelAPI = Http::withoutVerifying()
                                    ->post('https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/38050146d5e214e9731bc939e9668c4e/contacts/'.$payment_to_query->phone_number.'/senderId/GeePay/message/'.$url_encoded_message);*/

                                $custom_response = [
                                    "success" => true,
                                    "message" => "transaction processed successfully",
                                    "date" => now()
                                ];

                                return response()->json($custom_response, 200);

                            } else if ($status_json['data']['transaction']['status'] == "TIP") {
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 0 //failed
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "Transaction not confirmed"
                                ];

                                return response()->json($custom_response, 400);
                            }else{
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 0 //failed
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "transaction invalid"
                                ];

                                return response()->json($custom_response, 400);
                            }

                        } else {

                            //payment still in progress
                            $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                'status' => 0 //failed
                            ]);

                            $custom_response = [
                                "success" => false,
                                "message" => "transaction failed"
                            ];

                            return response()->json($custom_response, 400);
                        }
                    }else{
                        $custom_response = [
                            "success" => false,
                            "message" => "try again later"
                        ];

                        return response()->json($custom_response, 400);
                    }
                }
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "this transaction is not pending"
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function instant_payment_confirmation_deleted(Request $request)
    {
        $payment_to_query = Payment::where('id', $request->id)->first();
        //get the record to query
        if(Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->count() > 0) {
            //Airtel Money payment confirmation
            if ($payment_to_query->payment_channel == "Airtel Money") {
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

                    //check payment confirmation

                    $payment_response = Http::withHeaders([
                        'X-Currency' => 'ZMW',
                        'X-Country' => 'ZM',
                        'Accept' => '*/*',
                        'Authorization' => 'Bearer ' . $token
                    ])->get('https://openapi.airtel.africa/standard/v1/payments/' . $payment_to_query->payment_reference_number);

                    $status_state = $payment_response->status();
                    $status_json = $payment_response->json();

                    if ($status_state == 200) {
                        //check if the key data exist
                        if (array_key_exists("data", $status_json)) {
                            if ($status_json['data']['transaction']['status'] == "TS") {
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 2 //successfull
                                ]);

                                //get current balance
                                $old_current_payment_balance = CurrentBalance::where('business_id',$payment_to_query->business_id)->first()->payments;

                                //get current balance
                                $old_accumulated_balance = AccumulativeBalance::where('business_id',$payment_to_query->business_id)->first()->payments;

                                //current balance transaction calculations
                                $new_current_balance = $old_current_payment_balance + $payment_to_query->received_amount;

                                //current balance transaction calculations
                                $new_accumulated_balance = $old_accumulated_balance + $payment_to_query->received_amount;

                                $update_payments_balance = CurrentBalance::where('business_id',$payment_to_query->business_id)->update([
                                    "payments" => $new_current_balance
                                ]);

                                $update_accumulated_balance = AccumulativeBalance::where('business_id',$payment_to_query->business_id)->update([
                                    "payments" => $new_accumulated_balance
                                ]);

                                //send out notifications
                                $business_contact = Business::where('id', $payment_to_query->business_id)->first();
                                //send confirmation email to the business client
                                Mail::to($business_contact->business_email)->send(new PaymentConfirmationMail($business_contact->business_name, $payment_to_query->received_amount, $payment_to_query->phone_number, $payment_to_query->payment_reference_number, "to business"));

                                //get frontend url
                                $frontend_url = FrontEndUrl::first()->domain;
                                //receipt url
                                $receipt_url = $frontend_url.'/'.$payment_to_query->short_url;

                                //send confirmation sms
                                $url_encoded_message = urlencode("Your payment of ZMW" . $payment_to_query->received_amount . " to " . $business_contact->business_name . " is successful. Download your receipt at: " . $receipt_url);

                                $sendSMS = Http::withoutVerifying()
                                    ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $payment_to_query->phone_number . '&api_key=121231313213123123');


                                /*$sendZamtelAPI = Http::withoutVerifying()
                                    ->post('https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/38050146d5e214e9731bc939e9668c4e/contacts/'.$payment_to_query->phone_number.'/senderId/GeePay/message/'.$url_encoded_message);*/

                                $custom_response = [
                                    "success" => true,
                                    "message" => "transaction processed successfully",
                                    "date" => now()
                                ];

                                return response()->json($custom_response, 200);

                            } else if ($status_json['data']['transaction']['status'] == "TIP") {
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 0 //failed
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "Transaction not confirmed"
                                ];

                                return response()->json($custom_response, 400);
                            }else{
                                //payment still in progress
                                $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                    'status' => 0 //failed
                                ]);

                                $custom_response = [
                                    "success" => false,
                                    "message" => "transaction invalid"
                                ];

                                return response()->json($custom_response, 400);
                            }

                        } else {

                            //payment still in progress
                            $update_status = Payment::where('payment_reference_number', $payment_to_query->payment_reference_number)->update([
                                'status' => 0 //failed
                            ]);

                            $custom_response = [
                                "success" => false,
                                "message" => "transaction failed"
                            ];

                            return response()->json($custom_response, 400);
                        }
                    }else{
                        $custom_response = [
                            "success" => false,
                            "message" => "try again later"
                        ];

                        return response()->json($custom_response, 400);
                    }
                }
            }
        }else{
            $custom_response = [
                "success" => false,
                "message" => "this transaction is not pending"
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function show_receipt_api(Request $request)
    {
        //get frontend url
        $frontend_url = FrontEndUrl::first()->domain;

        $requested_short_url = $frontend_url."/r/".$request->shortUrl;

        if(Payment::where('short_url', $requested_short_url)->count() > 0){
            $receipt = Payment::where('short_url', $requested_short_url)->first();

            //last three digits of the customer number used
            $phone_number = substr($receipt->phone_number, -3);

            $custom_response = [
                "business_name" => Business::where('id', $receipt->business_id)->first()->business_name,
                "business_logo" => "https://businessstagging.ontechcloud.tech/imgz/geepay_logo_green.png",
                "business_phone_number" => "260".Business::where('id', $receipt->business_id)->first()->business_phone_number,
                "customer_name" => Customer::where('phone_number', $receipt->phone_number)->first()->name ?? "",
                "customer_phone_number" => $phone_number,
                "payment_reference_number" => $receipt->payment_reference_number,
                "amount" => floatval($receipt->received_amount),
                "short_url" => $receipt->short_url,
                "payment_channel" => $receipt->payment_channel,
                "description" => $receipt->description,
                "updated_at" => $receipt->updated_at,
                "is_refunded" => $receipt->is_refunded
            ];

            return response()->json($custom_response, 200);
        }else{
            $custom_response = [
                "success" => false,
                "message" => "Transaction not recognised"
            ];

            return response()->json($custom_response, 400);
        }

    }

    public function excel_export(Request $request)
    {
        // retrieve payments from the database
        $payments = Payment::all();

        // create a new Excel spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // add column headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Amount');
        $sheet->setCellValue('C1', 'Status');

        // add payment data
        $row = 2;
        foreach ($payments as $payment) {
            $sheet->setCellValue('A' . $row, $payment->id);
            $sheet->setCellValue('B' . $row, $payment->received_amount);
            $sheet->setCellValue('C' . $row, $payment->status);
            $row++;
        }

        // create a writer object
        $writer = new Xlsx($spreadsheet);
        // set headers for file download
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="payments.xlsx"',
            'Cache-Control' => 'max-age=0',
        ];

        // create a response object with the Excel file as the content
        $response = response($writer->save('php://output'), 200, $headers);

        // return the response
        return $response;
    }

    public function create_payment(Request $request)
    {
        $url = Url::fromString('https://ontechzambia.tech/');
        //$shortenedUrl = Url::fromString('https://oontechzambia.tech/')->withQueryParameter('u', 'some-long-url');
        $shortenedUrl = Url::fromString('https://ontechzambia.tech/');

        return response()->json((string) $shortenedUrl);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_payments = Payment::selectRaw('business_id,business_name,account_number,sum(received_amount) as total')
            ->where('status', 2)
            ->groupBy('business_id','business_name','account_number')
            ->orderBy('total', 'desc')
            ->get();
        return view('payments.index', compact('all_payments'));
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
    public function show($id)
    {
        $all_payments = Payment::find($id);
        $business_name = $all_payments->business_name;
        return view('payments.show', compact('all_payments','business_name','id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $delete_selected = Payment::where('id', $request->id)->update([
            "is_deleted" => 1
        ]);

        $custom_response = [
            "success" => true,
            "message" => "payment transaction deleted successfully"
        ];

        return response()->json($custom_response, 200);
    }
}
