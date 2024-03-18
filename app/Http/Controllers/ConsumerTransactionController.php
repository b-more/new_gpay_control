<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\ConsumerCurrentBalanceLimit;
use App\Models\ConsumerDailyWithdrawLimit;
use App\Models\FrontEndUrl;
use App\Models\Payment;
use App\Models\CommissionReceived;
use App\Models\PaymentCommission;
use App\Models\Business;
use App\Models\ConsumerBalance;
use App\Models\ConsumerCommissionStructure;
use App\Models\ConsumerTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConsumerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function authenticateAction(Request $request){
        $request->validate([
            "password" => "required"
        ]);

        $user = Consumer::where('phone_number',$request->phone_number)->first();
        if(Hash::check($request->password, $user->password))
        {
            //custom response
            $response = [
                "success" => true,
                "message" => "User authenticated successfully"
            ];

            return response()->json($response,200);
        }else{
            //custom response
            $response = [
                "success" => false,
                "message" => "Oops, wrong credentials",
            ];

            return response()->json($response,400);
        }
    }
    function generatePaymentReferenceNumber()
    {
        $prefix = 'W'; // Prefix for the account number
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

    function generate_short_url()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $res = "";
        for ($i = 0; $i < 5; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        // Check if the short url already exists in the database
        if (DB::table('payments')->where('short_url', $res)->exists()) {
            // If the short url already exists, generate a new one recursively
            return $this->generate_short_url();
        }

        return $res;
    }

    public function send_money_mobile(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        $sender_details = Consumer::where('id', Auth::user()->id)->first();

        //Check which payment channel has been selected
        if ($request->channel == 'GeePay') {
            //check if the reciepient is registered on Geepay

            $sender_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;
            if (floatval($sender_current_balance) >= floatval($request->amount)) {
                if (Consumer::where('phone_number', $request->phone_number)->count() > 0) {
                    $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();
                    //Customer registered on GeePay
                    //receiver details
                    $receiver_details = Consumer::where('phone_number', $request->phone_number)->first();

                    //get receiver current balance
                    $receiver_current_balance = ConsumerBalance::where('consumer_id', $receiver_details->id)->first()->balance;

                    //new_receiver_balance
                    $new_receiver_balance = floatval($receiver_current_balance) + floatval($request->amount);

                    //AML check if receiver intended balance is in valid range
                    if($new_receiver_balance > floatval(ConsumerCurrentBalanceLimit::where('is_active', 1)->first()->amount))
                    {
                        //current balance exceeded the limit
                        $custom_response = [
                            "success" => false,
                            "message" => "You can not  make this transaction because the receiver has reached maximum limit. Try again later"
                        ];

                        return response()->json($custom_response, 400);
                    }else{
                        //check the daily limit
                        $currentDate = Carbon::now()->toDateString();

                        $my_daily_transaction = ConsumerTransaction::where("consumer_id", $sender_details->id)->where("type", "sent")->whereDate('created_at', $currentDate)
                            ->sum('amount');

                        $total_intended = floatval($my_daily_transaction) + floatval($request->amount);

                        if($total_intended > floatval(ConsumerDailyWithdrawLimit::where('is_active',1)->first()->amount))
                        {
                            //current balance exceeded the limit
                            $custom_response = [
                                "success" => false,
                                "message" => "You can not  make this transaction because you have reached your daily maximum limit. Try again later"
                            ];

                            return response()->json($custom_response, 400);
                        }else{
                            //receiver transaction details
                            $receiver_transaction_details = ConsumerTransaction::create([
                                "consumer_id" => $receiver_details->id,
                                "consumer_name" => $receiver_details->name,
                                "payment_reference_number" => $payment_transaction_reference_number,
                                "type" => "received", //send,deposit,transfer,pay
                                "partner" => $sender_details->phone_number, //phone number, business_id, Agent_id, third-party name
                                "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                                "amount" => $request->amount,
                                "status" => 2, //success
                                "channel" => "GeePay",
                                "custom_message" => $request->custom_message,
                                "phone_number" => $receiver_details->phone_number

                            ]);

                            $receiver_transaction_details->save();

                            //new receiver balance update
                            $new_receiver_balance_update = ConsumerBalance::where('consumer_id', $receiver_details->id)->update([
                                "balance" => $new_receiver_balance
                            ]);

                            //receiver message body
                            $message = "You have received the sum of ZMW" . $request->amount . " from " . $sender_details->phone_number . " " . $sender_details->name . ". Your GeePay Balance is ZMW" . number_format($new_receiver_balance, 2) . " Txn Id " . $payment_transaction_reference_number;

                            //send receiver sms notification
                            $this->sendOntechSmsNotification($message, $request->phone_number);

                            //set sender current balance
                            $new_sender_balance = floatval($sender_current_balance) - floatval($request->amount);

                            //sender transaction details
                            $sender_transaction_details = ConsumerTransaction::create([
                                "consumer_id" => $sender_details->id,
                                "consumer_name" => $sender_details->name,
                                "payment_reference_number" => $payment_transaction_reference_number,
                                "type" => "sent", //send,deposit,transfer,pay
                                "partner" => $receiver_details->phone_number, //phone number, business_id, Agent_id, third-party name
                                "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                                "amount" => $request->amount,
                                "status" => 2, //success
                                "channel" => "GeePay",
                                "custom_message" => $request->custom_message,
                                "phone_number" => $sender_details->phone_number
                            ]);

                            $sender_transaction_details->save();

                            //new sender balance update
                            $new_sender_balance_update = ConsumerBalance::where('consumer_id', $sender_details->id)->update([
                                "balance" => $new_sender_balance
                            ]);

                            //receiver message body
                            $sender_message = "You have sent  ZMW" . $request->amount . " to " . $receiver_details->phone_number . " " . $receiver_details->name . ". Your GeePay Balance is ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $payment_transaction_reference_number;

                            //send receiver sms notification
                            $this->sendOntechSmsNotification($sender_message, $sender_details->phone_number);

                            //Insufficient credit
                            $custom_response = [
                                'success' => true,
                                'message' => 'Your transaction was successful.'
                            ];
                            return response()->json($custom_response, 200);
                        }
                    }


                } else {
                    //Send an SMS to the recipient to download the Geepay app and create an Account
                    $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();
                    //Customer registered on GeePay
                    //receiver details

                    //receiver transaction details
                    $receiver_transaction_details = ConsumerTransaction::create([
                        "payment_reference_number" => $payment_transaction_reference_number,
                        "type" => "received", //send,deposit,transfer,pay
                        "partner" => $sender_details->phone_number, //phone number, business_id, Agent_id, third-party name
                        "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                        "amount" => $request->amount,
                        "status" => 2, //success
                        "channel" => "GeePay",
                        "custom_message" => $request->custom_message,
                        "phone_number" => $request->phone_number,
                        "lead" => 1
                    ]);

                    $receiver_transaction_details->save();

                    //receiver message body
                    $receiver_message = "You have received the sum of ZMW" . $request->amount . " from " . $sender_details->phone_number . " " . $sender_details->name . ". Download GeePay App on https://business.crmzambia.com/app to access your money. Txn Id " . $payment_transaction_reference_number;

                    //send receiver sms notification
                    $this->sendOntechSmsNotification($receiver_message,$request->phone_number);
                    //set sender current balance
                    $new_sender_balance = (floatval($sender_current_balance) - floatval($request->amount)) + floatval($request->bonus);

                    //sender transaction details
                    $sender_transaction_details = ConsumerTransaction::create([
                        "consumer_id" => $sender_details->id,
                        "consumer_name" => $sender_details->name,
                        "payment_reference_number" => $payment_transaction_reference_number,
                        "type" => "sent", //send,deposit,transfer,pay
                        "partner" => $request->phone_number, //phone number, business_id, Agent_id, third-party name
                        "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                        "amount" => $request->amount,
                        "status" => 2, //success
                        "channel" => "GeePay",
                        "custom_message" => $request->custom_message,
                        "phone_number" => $sender_details->phone_number,
                        "bonus" => $request->bonus
                    ]);

                    $sender_transaction_details->save();

                    //new sender balance update
                    $new_sender_balance_update = ConsumerBalance::where('consumer_id', $sender_details->id)->update([
                        "balance" => $new_sender_balance
                    ]);

                    //receiver message body
                    $sender_message = "You have sent  ZMW" . $request->amount . " to " . $request->phone_number . ".Your GeePay Balance is now ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $payment_transaction_reference_number;

                    //send receiver sms notification
                    $this->sendOntechSmsNotification($sender_message,$sender_details->phone_number);

                    //receiver message body
                    $url_bonus_encoded_message = "You have received ZMW" . $request->bonus . " bonus for sending ZMW" . $request->amount . " to " . $request->phone_number . " using your GeePay account. Txn Id " . $payment_transaction_reference_number;

                    //send receiver sms notification
                    $this->sendOntechSmsNotification($url_bonus_encoded_message, $sender_details->phone_number);

                    //Insufficient credit
                    $custom_response = [
                        'success' => true,
                        'message' => 'Your transaction was successful.'
                    ];
                    return response()->json($custom_response, 200);
                }
            } else {
                //Insufficient credit
                $custom_response = [
                    'success' => false,
                    'message' => 'You have insufficient balance to perform this transaction.'
                ];
                return response()->json($custom_response, 400);
            }
        } elseif ($request->channel == 'MNO') {
            //Process sending to MNOs with a commission charge
            $sender_details = Consumer::where('id', Auth::user()->id)->first();

            $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();

            //Check if the available balance is enough for the transaction plus commission
            $current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;
            $wallet_to_mno = PaymentCommission::where('category', 'wallet to mobile')->first();

            //Calculate Commission Value
            $calculated_commission = (((floatval($wallet_to_mno->cgrate_percentage) + floatval($wallet_to_mno->geepay_percentage))/100) * floatval($request->amount)) + (floatval($wallet_to_mno->cgrate_fixed_charge) + floatval($wallet_to_mno->geepay_fixed_charge));
            $total_amount_charged = $calculated_commission + floatval($request->amount);

            $currentDate = Carbon::now()->toDateString();

            $my_daily_transaction = ConsumerTransaction::where("consumer_id", $sender_details->id)->where("type", "sent")->whereDate('created_at', $currentDate)
                ->sum('amount');

            $total_intended = floatval($my_daily_transaction) + $total_amount_charged;

            if($total_intended > floatval(ConsumerDailyWithdrawLimit::where('is_active',1)->first()->amount))
            {
                //current balance exceeded the limit
                $custom_response = [
                    "success" => false,
                    "message" => "You can not  make this transaction because you have reached your daily maximum limit. Try again later"
                ];

                return response()->json($custom_response, 400);
            }else{
                if (floatval($current_balance) >= $total_amount_charged) {

                    $nine_digit_phone_number = $request->phone_number;

                    Log::info("MNO phone number to send to: ".$nine_digit_phone_number."  but the full number is ".$request->phone_number);
                    //check prefix
                    if (str_starts_with($nine_digit_phone_number, '077') || str_starts_with($nine_digit_phone_number, '097'))
                    {
                        //sender transaction details
                        $sender_transaction_details = ConsumerTransaction::create([
                            "consumer_id" => $sender_details->id,
                            "consumer_name" => $sender_details->name,
                            "payment_reference_number" => $payment_transaction_reference_number,
                            "type" => "transfer", //send,deposit,transfer,pay
                            "partner" => $request->phone_number, //phone number, business_id, Agent_id, third-party name
                            "partner_type" => "airtel money", //Wallet, Business,Agent,third-party name
                            "amount" => $request->amount,
                            "status" => 1, //pending
                            "channel" => "GeePay",
                            "custom_message" => $request->custom_message,
                            "phone_number" => $sender_details->phone_number
                        ]);

                        $sender_transaction_details->save();

                        $new_sender_balance = (floatval($current_balance) - floatval($request->amount));

                        return $this->airtel_money_disbursement($request->phone_number,floatval($request->amount), $payment_transaction_reference_number, $sender_transaction_details, $new_sender_balance);


                    }elseif (str_starts_with($nine_digit_phone_number, '076') || str_starts_with($nine_digit_phone_number, '096'))
                    {
                        $custom_response = [
                            'success' => false,
                            'message' => 'You have have entered an invalid number.'
                        ];
                        return response()->json($custom_response, 400);
                    }elseif (str_starts_with($nine_digit_phone_number, '075') || str_starts_with($nine_digit_phone_number, '095'))
                    {
                        $custom_response = [
                            'success' => false,
                            'message' => 'You have have entered an invalid number.'
                        ];
                        return response()->json($custom_response, 400);
                    } else {
                        $custom_response = [
                            'success' => false,
                            'message' => 'You have have entered an invalid number.'
                        ];
                        return response()->json($custom_response, 400);
                    }
                } else {
                    //Insufficient credit
                    $custom_response = [
                        'success' => false,
                        'message' => 'You have insufficient balance to perform this transaction.'
                    ];
                    return response()->json($custom_response, 400);
                }
            }
        } elseif ($request->channel == 'Bank') {
        } else {
            //Invalid Option
            $custom_response = [
                'success' => false,
                'message' => 'You have selected an invalid option.'
            ];
            return response()->json($custom_response, 400);
        }

        //Check if the phone number is registered with GeePay

        //Check if balance is enough for the transaction

    }

    public function deposit_money_mobile(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        $sender_details = Consumer::where('id', Auth::user()->id)->first();

        //Check which payment channel has been selected
        if ($request->channel == 'GeePay') {
            //check if the reciepient is registered on Geepay

            $sender_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;
            if (floatval($sender_current_balance) >= floatval($request->amount)) {
                if (Consumer::where('phone_number', $request->phone_number)->count() > 0) {
                    $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();
                    //Customer registered on GeePay
                    //receiver details
                    $receiver_details = Consumer::where('phone_number', $request->phone_number)->first();

                    //get receiver current balance
                    $receiver_current_balance = ConsumerBalance::where('consumer_id', $receiver_details->id)->first()->balance;

                    //new_receiver_balance
                    $new_receiver_balance = floatval($receiver_current_balance) + floatval($request->amount);

                    //AML check if receiver intended balance is in valid range
                    if($new_receiver_balance > floatval(ConsumerCurrentBalanceLimit::where('is_active', 1)->first()->amount))
                    {
                        //current balance exceeded the limit
                        $custom_response = [
                            "success" => false,
                            "message" => "You can not  make this transaction because the receiver has reached maximum limit. Try again later"
                        ];

                        return response()->json($custom_response, 400);
                    }else{
                        //check the daily limit
                        $currentDate = Carbon::now()->toDateString();

                        $my_daily_transaction = ConsumerTransaction::where("consumer_id", $sender_details->id)->where("type", "sent")->whereDate('created_at', $currentDate)
                            ->sum('amount');

                        $total_intended = floatval($my_daily_transaction) + floatval($request->amount);

                        if($total_intended > floatval(ConsumerDailyWithdrawLimit::where('is_active',1)->first()->amount))
                        {
                            //current balance exceeded the limit
                            $custom_response = [
                                "success" => false,
                                "message" => "You can not  make this transaction because you have reached your daily maximum limit. Try again later"
                            ];

                            return response()->json($custom_response, 400);
                        }else{
                            //receiver transaction details
                            $receiver_transaction_details = ConsumerTransaction::create([
                                "consumer_id" => $receiver_details->id,
                                "consumer_name" => $receiver_details->name,
                                "payment_reference_number" => $payment_transaction_reference_number,
                                "type" => "received", //send,deposit,transfer,pay
                                "partner" => $sender_details->phone_number, //phone number, business_id, Agent_id, third-party name
                                "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                                "amount" => $request->amount,
                                "status" => 2, //success
                                "channel" => "GeePay",
                                "custom_message" => $request->custom_message,
                                "phone_number" => $receiver_details->phone_number

                            ]);

                            $receiver_transaction_details->save();

                            //new receiver balance update
                            $new_receiver_balance_update = ConsumerBalance::where('consumer_id', $receiver_details->id)->update([
                                "balance" => $new_receiver_balance
                            ]);

                            //receiver message body
                            $message = "You have received the sum of ZMW" . $request->amount . " from " . $sender_details->phone_number . " " . $sender_details->name . ". Your GeePay Balance is ZMW" . number_format($new_receiver_balance, 2) . " Txn Id " . $payment_transaction_reference_number;

                            //send receiver sms notification
                            $this->sendOntechSmsNotification($message, $request->phone_number);

                            //set sender current balance
                            $new_sender_balance = floatval($sender_current_balance) - floatval($request->amount);

                            //sender transaction details
                            $sender_transaction_details = ConsumerTransaction::create([
                                "consumer_id" => $sender_details->id,
                                "consumer_name" => $sender_details->name,
                                "payment_reference_number" => $payment_transaction_reference_number,
                                "type" => "sent", //send,deposit,transfer,pay
                                "partner" => $receiver_details->phone_number, //phone number, business_id, Agent_id, third-party name
                                "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                                "amount" => $request->amount,
                                "status" => 2, //success
                                "channel" => "GeePay",
                                "custom_message" => $request->custom_message,
                                "phone_number" => $sender_details->phone_number
                            ]);

                            $sender_transaction_details->save();

                            //new sender balance update
                            $new_sender_balance_update = ConsumerBalance::where('consumer_id', $sender_details->id)->update([
                                "balance" => $new_sender_balance
                            ]);

                            //receiver message body
                            $sender_message = "You have sent  ZMW" . $request->amount . " to " . $receiver_details->phone_number . " " . $receiver_details->name . ". Your GeePay Balance is ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $payment_transaction_reference_number;

                            //send receiver sms notification
                            $this->sendOntechSmsNotification($sender_message, $sender_details->phone_number);

                            //Insufficient credit
                            $custom_response = [
                                'success' => true,
                                'message' => 'Your transaction was successful.'
                            ];
                            return response()->json($custom_response, 200);
                        }
                    }


                } else {
                    //Send an SMS to the recipient to download the Geepay app and create an Account
                    $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();
                    //Customer registered on GeePay
                    //receiver details

                    //receiver transaction details
                    $receiver_transaction_details = ConsumerTransaction::create([
                        "payment_reference_number" => $payment_transaction_reference_number,
                        "type" => "received", //send,deposit,transfer,pay
                        "partner" => $sender_details->phone_number, //phone number, business_id, Agent_id, third-party name
                        "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                        "amount" => $request->amount,
                        "status" => 2, //success
                        "channel" => "GeePay",
                        "custom_message" => $request->custom_message,
                        "phone_number" => $request->phone_number,
                        "lead" => 1
                    ]);

                    $receiver_transaction_details->save();

                    //receiver message body
                    $receiver_message = "You have received the sum of ZMW" . $request->amount . " from " . $sender_details->phone_number . " " . $sender_details->name . ". Download GeePay App on https://business.crmzambia.com/app to access your money. Txn Id " . $payment_transaction_reference_number;

                    //send receiver sms notification
                    $this->sendOntechSmsNotification($receiver_message,$request->phone_number);
                    //set sender current balance
                    $new_sender_balance = (floatval($sender_current_balance) - floatval($request->amount)) + floatval($request->bonus);

                    //sender transaction details
                    $sender_transaction_details = ConsumerTransaction::create([
                        "consumer_id" => $sender_details->id,
                        "consumer_name" => $sender_details->name,
                        "payment_reference_number" => $payment_transaction_reference_number,
                        "type" => "sent", //send,deposit,transfer,pay
                        "partner" => $request->phone_number, //phone number, business_id, Agent_id, third-party name
                        "partner_type" => "wallet", //Wallet, Business,Agent,third-party name
                        "amount" => $request->amount,
                        "status" => 2, //success
                        "channel" => "GeePay",
                        "custom_message" => $request->custom_message,
                        "phone_number" => $sender_details->phone_number,
                        "bonus" => $request->bonus
                    ]);

                    $sender_transaction_details->save();

                    //new sender balance update
                    $new_sender_balance_update = ConsumerBalance::where('consumer_id', $sender_details->id)->update([
                        "balance" => $new_sender_balance
                    ]);

                    //receiver message body
                    $sender_message = "You have sent  ZMW" . $request->amount . " to " . $request->phone_number . ".Your GeePay Balance is now ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $payment_transaction_reference_number;

                    //send receiver sms notification
                    $this->sendOntechSmsNotification($sender_message,$sender_details->phone_number);

                    //receiver message body
                    $url_bonus_encoded_message = "You have received ZMW" . $request->bonus . " bonus for sending ZMW" . $request->amount . " to " . $request->phone_number . " using your GeePay account. Txn Id " . $payment_transaction_reference_number;

                    //send receiver sms notification
                    $this->sendOntechSmsNotification($url_bonus_encoded_message, $sender_details->phone_number);

                    //Insufficient credit
                    $custom_response = [
                        'success' => true,
                        'message' => 'Your transaction was successful.'
                    ];
                    return response()->json($custom_response, 200);
                }
            } else {
                //Insufficient credit
                $custom_response = [
                    'success' => false,
                    'message' => 'You have insufficient balance to perform this transaction.'
                ];
                return response()->json($custom_response, 400);
            }
        } elseif ($request->channel == 'MNO') {
            //Process sending to MNOs with a commission charge
            $sender_details = Consumer::where('id', Auth::user()->id)->first();

            $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();

            //Check if the available balance is enough for the transaction plus commission
            $current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;
            $wallet_to_mno = PaymentCommission::where('category', 'mobile to wallet')->first();

            //Calculate Commission Value
            $calculated_commission = (((floatval($wallet_to_mno->cgrate_percentage) + floatval($wallet_to_mno->geepay_percentage))/100) * floatval($request->amount)) + (floatval($wallet_to_mno->cgrate_fixed_charge) + floatval($wallet_to_mno->geepay_fixed_charge));
            $total_amount_charged = $calculated_commission + floatval($request->amount);

            $currentDate = Carbon::now()->toDateString();

            $my_daily_transaction = ConsumerTransaction::where("consumer_id", $sender_details->id)->where("type", "sent")->whereDate('created_at', $currentDate)
                ->sum('amount');

            $total_intended = floatval($my_daily_transaction) + $total_amount_charged;

            if($total_intended + floatval($current_balance) > floatval(ConsumerCurrentBalanceLimit::where('is_active',1)->first()->amount))
            {
                //current balance exceeded the limit
                $custom_response = [
                    "success" => false,
                    "message" => "You can not  make this transaction because you have reached your wallet maximum balance."
                ];

                return response()->json($custom_response, 400);
            }else{

                    $nine_digit_phone_number = $request->phone_number;

                    Log::info("MNO phone number to send to: ".$nine_digit_phone_number."  but the full number is ".$request->phone_number);
                    //check prefix
                    if (str_starts_with($nine_digit_phone_number, '077') || str_starts_with($nine_digit_phone_number, '097'))
                    {
                        return $this->konse_konse_deposit_mobile_money($request->amount, $request->phone_number, $payment_transaction_reference_number, $sender_details,"Airtel Money", $current_balance);
                    }elseif (str_starts_with($nine_digit_phone_number, '076') || str_starts_with($nine_digit_phone_number, '096'))
                    {
                        return $this->konse_konse_deposit_mobile_money($request->amount, $request->phone_number, $payment_transaction_reference_number, $sender_details,"MTN Money", $current_balance);
                    }elseif (str_starts_with($nine_digit_phone_number, '075') || str_starts_with($nine_digit_phone_number, '095'))
                    {
                        return $this->konse_konse_deposit_mobile_money($request->amount, $request->phone_number, $payment_transaction_reference_number, $sender_details,"ZAMTEL Money", $current_balance);
                    } else {
                        $custom_response = [
                            'success' => false,
                            'message' => 'You have have entered an invalid number.'
                        ];
                        return response()->json($custom_response, 400);
                    }

            }
        } elseif ($request->channel == 'Bank') {
        } else {
            //Invalid Option
            $custom_response = [
                'success' => false,
                'message' => 'You have selected an invalid option.'
            ];
            return response()->json($custom_response, 400);
        }

        //Check if the phone number is registered with GeePay

        //Check if balance is enough for the transaction

    }

    public function get_user_by_qr_code(Request $request)
    {
        $request->validate([
            'qr_code' => 'required'
        ]);

        if ($request->receiver_type == "business") {
            //business logic
            if (Business::where('payment_checkout', $request->qr_code)->count() > 0) {
                $business = Business::where('payment_checkout', $request->qr_code)->first();

                $custom_response = [
                    'success' => true,
                    'message' => 'Business fetched successfully',
                    'recepient_type' => 'business',
                    'business_name' => $business->business_name,
                    'business_logo' => $business->business_logo,
                    'business_id' => $business->id
                ];
                return response()->json($custom_response, 200);
            } else {
                $custom_response = [
                    'success' => false,
                    'message' => 'Business not yet registered on GeePay'
                ];
                return response()->json($custom_response, 404);
            }
        } else if ($request->receiver_type == "consumer") {
            if (Consumer::where('qr_code', $request->qr_code)->count() > 0) {
                $receiver_details = Consumer::where('qr_code', $request->qr_code)->first();

                $custom_response = [
                    'success' => true,
                    'message' => 'User fetched successfully',
                    'recepient_type' => 'consumer',
                    'name' => $receiver_details->name,
                    'phone_number' => $receiver_details->phone_number,
                    'avatar' => $receiver_details->avatar
                ];

                return response()->json($receiver_details, 200);
            } else {
                $custom_response = [
                    'success' => false,
                    'message' => 'User not yet registered on GeePay'
                ];
                return response()->json($custom_response, 404);
            }
        } else {
            $custom_response = [
                "success" => false,
                "message" => "Unrecognized receipient",
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function send_money_to_business(Request $request)
    {
        $business = Business::where('id', $request->id)->first();

        $ref_number = $this->generatePaymentReferenceNumber();

        if (ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance >= $request->amount) {
            $sender_current_balance = ConsumerBalance::where('consumer_id', Auth::user()->id)->first()->balance;
            //calculate commission
            $commission_percentage = PaymentCommission::where('category', 'collections')->first()->calculations;

            //calculate GeePay commission value
            $commission_value = ($commission_percentage * $request->amount);

            //calculate payout amount
            $payout_amount = $request->amount - $commission_value;

            //check the daily limit
            $currentDate = Carbon::now()->toDateString();

            $my_daily_transaction = ConsumerTransaction::where("consumer_id", Auth::user()->id)->where("type", "sent")->whereDate('created_at', $currentDate)
                ->sum('amount');

            $total_intended = $my_daily_transaction + $request->amount;

            if($total_intended > ConsumerDailyWithdrawLimit::where('is_active',1)->first()->amount)
            {
                //current balance exceeded the limit
                $custom_response = [
                    "success" => false,
                    "message" => "You can not  make this transaction because you have reached your daily maximum limit. Try again later"
                ];

                return response()->json($custom_response, 400);
            }else{
                //save commission
                $new_commission = CommissionReceived::create([
                    "business_id" => $business->id,
                    "payment_reference_number" => $ref_number,
                    "amount" => $commission_value
                ]);

                $new_commission->save();

                $short_url_code = $this->generate_short_url();

                //save payment
                $new_payment = Payment::create([
                    "business_id" => $business->id,
                    "payment_method_id" => 1,
                    "customer_id" => Auth::user()->id,
                    "payment_channel" => "GeePay",
                    "business_name" => $business->business_name,
                    "account_number" => $business->account_number,
                    "payment_reference_number" => $ref_number,
                    "txn_number" => $ref_number,
                    "phone_number" => Auth::user()->phone_number,
                    "description" => "wallet to business",
                    "received_amount" => $request->amount,
                    "commission_charged" => $commission_value,
                    "payout_amount" => $payout_amount,
                    "short_url" => $short_url_code,
                    "status" => 2
                ]);

                $new_payment->save();

                //get frontend url
                $frontend_url = FrontEndUrl::first()->domain;

                //the shortened url
                $receipt_url = $frontend_url . '/r/' . $short_url_code;

                //save consumer transaction record
                //set sender current balance
                $new_sender_balance = ($sender_current_balance - $request->amount);

                //sender transaction details
                $sender_transaction_details = ConsumerTransaction::create([
                    "consumer_id" => Auth::user()->id,
                    "consumer_name" => Auth::user()->name,
                    "payment_reference_number" => $ref_number,
                    "type" => "sent", //send,deposit,transfer,pay
                    "partner" => $business->id, //phone number, business_id, Agent_id, third-party name
                    "partner_type" => "Business", //Wallet, Business,Agent,third-party name
                    "amount" => $request->amount,
                    "status" => 2, //success
                    "channel" => "GeePay",
                    "custom_message" => "wallet to business",
                    "phone_number" => Auth::user()->phone_number
                ]);

                $sender_transaction_details->save();

                //new sender balance update
                $new_sender_balance_update = ConsumerBalance::where('consumer_id', Auth::user()->id)->update([
                    "balance" => $new_sender_balance
                ]);

                //Sender message body
                $url_encoded_message = "You have sent  ZMW" . $request->amount . " to " . $business->business_name . ".Your GeePay Balance is now ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $ref_number;

                //send receiver sms notification
                $this->sendOntechSmsNotification($url_encoded_message, Auth::user()->phone_number);

                $custom_response = [
                    "success" => true,
                    "message" => "Money sent successfully",
                ];

                return response()->json($custom_response, 200);
            }
        } else {
            $custom_response = [
                "success" => false,
                "message" => "You have insufficient balance to perform this transaction",
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function transaction_history()
    {
        $transactions = ConsumerTransaction::where('consumer_id', Auth::user()->id)->orderBy('created_at','desc')->get();

        $custom_response = [
            "success" => true,
            "message" => "transaction fetched successfully",
            "data" => $transactions
        ];

        return response()->json($custom_response, 200);
    }

    function airtel_money_disbursement($phone_number, $amount, $ref_number,$sender_transaction_details, $new_sender_balance)
    {
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
            CURLOPT_POSTFIELDS => '{"client_id":"19fd47b3-6c68-4759-b360-f0f2c4592e07","client_secret":"4dd9fea0-3c5d-4df5-a9ff-369bd16f511c","grant_type":"client_credentials"}',
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

        Log::info("Airtel Money ".$token);


        $fomartted_number = substr($phone_number, -9);

        Log::info("Airtel Money MSISDN ".$fomartted_number);

        if($token)
        {
            $headers = [
                'Authorization' => 'Bearer '.$token,
                'X-Country' => 'ZM',
                'X-Currency' => 'ZMW',
                'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)
                ->post('https://openapi.airtel.africa/standard/v1/disbursements/', [
                    "reference" => "GeePay Wallet to Mobile",
                    "pin" => "Zt6uoovDi+npFfsvmyzTZGAS7Hxi1gNmq+pZFUNflq6fNkzPFqL8sc/hNvnX0bhIE8l3FmXNZqXjOnI3XvlcPIf3nhtYHvqbiUtpW2XV+63LXi52LpPDSA3BywlGVxhQP1tzVl+kGCXQgXT+K7rFqI2U4c3bDPSEyTp2bweVPsY=",
                    "payee" => [
                        "country" => "ZM",
                        "currency" => "ZMW",
                        "msisdn" => $fomartted_number,
                        "name" => $sender_transaction_details->consumer_name
                    ],
                    "transaction" => [
                        "amount" => $amount,
                        "type" => "B2C",
                        "id" => $ref_number,
                    ],
                ]);

            $status_state = $response->status();
            $status_json = $response->json();

            Log::info("Response from Airtel Disbusement", ["res" => $status_json]);

            if ($status_state == 200) {
                if (array_key_exists("data", $status_json)) {
                    if ($status_json['data']['transaction']['status'] == "TS") {
                        //successful transaction
                        Log::info($status_json['status']['message']);

                        $update_sender_record = ConsumerTransaction::where('id', $sender_transaction_details->id)->update([
                            "custom_message" => $status_json['status']['message'],
                            "status" => 2
                        ]);

                        //new sender balance update
                        $new_sender_balance_update = ConsumerBalance::where('consumer_id', $sender_transaction_details->consumer_id)->update([
                            "balance" => $new_sender_balance
                        ]);

                        //receiver message body
                        $sender_message = "You have transferred  ZMW" . $amount . " to Airtel Money wallet ".$phone_number."Your GeePay Balance is ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $ref_number;

                        //receiver message body
                        $receiver_message = $sender_transaction_details->consumer_name." has sent ZMW" . $amount . " to your Airtel Money wallet ".$phone_number."from GeePay. Txn No ".$ref_number;

                        //send receiver sms notification
                        $this->sendOntechSmsNotification($sender_message, $sender_transaction_details->phone_number);

                        //send receiver sms notification
                        $this->sendOntechSmsNotification($receiver_message, $phone_number);

                        //Success
                        $custom_response = [
                            'success' => true,
                            'message' => 'Your transaction was successful.',
                            'txn' => $status_json['data']['transaction']['reference_id']
                        ];
                        return response()->json($custom_response, 200);

                    }else{
                        //failed
                        Log::info($status_json['status']['message']);

                        $update_sender_record = ConsumerTransaction::where('id', $sender_transaction_details->id)->update([
                            "custom_message" => $status_json['status']['message'],
                            "status" => 0
                        ]);

                        //Success
                        $custom_response = [
                            'success' => false,
                            'message' => 'Your transaction has failed.',
                            'txn' => $status_json['data']['transaction']['reference_id']
                        ];
                        return response()->json($custom_response, 400);
                    }
                }else{
                    $update_sender_record = ConsumerTransaction::where('id', $sender_transaction_details->id)->update([
                        "custom_message" => "failed",
                        "status" => 0
                    ]);

                    //Success
                    $custom_response = [
                        'success' => false,
                        'message' => 'Your transaction has failed.',
                        'txn' => $status_json['data']['transaction']['reference_id']
                    ];
                    return response()->json($custom_response, 400);
                }
            }
        }



    }

    function konse_konse_deposit_mobile_money($amount, $phone_number, $ref_number, $sender_details, $mno, $sender_current_balance)
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
            //success
            $paymentID = (string)$xml->xpath('//env:Envelope/env:Body/ns2:processCustomerPaymentResponse/return/paymentID')[0];

           /* //receiver message body
            $message = "You have received the sum of ZMW" . $amount . " from " . $sender_details->phone_number . " " . $sender_details->name . ". fom GeePay App. Txn Id " . $ref_number;

            //send receiver sms notification
            $this->sendOntechSmsNotification($message, $phone_number);*/

            //sender transaction details
            $sender_transaction_details = ConsumerTransaction::create([
                "consumer_id" => $sender_details->id,
                "consumer_name" => $sender_details->name,
                "payment_reference_number" => $ref_number,
                "type" => "deposit", //send,deposit,transfer,pay
                "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                "partner_type" => $mno, //Wallet, Business,Agent,third-party name
                "amount" => $amount,
                "status" => 2, //success
                "channel" => "GeePay",
                "custom_message" => "Received from Mobile Money",
                "phone_number" => $sender_details->phone_number
            ]);

            $sender_transaction_details->save();

            //set sender current balance
            $new_sender_balance = $sender_current_balance + $amount;


            //new sender balance update
            $sender_balance_update = ConsumerBalance::where('consumer_id', $sender_details->id)->update([
                "balance" => $new_sender_balance
            ]);

            //receiver message body
            $sender_message = "You have deposited  ZMW" . $amount  . "Your GeePay Balance is ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $ref_number;

            //send receiver sms notification
            $this->sendOntechSmsNotification($sender_message, $sender_details->phone_number);

            //Insufficient credit
            $custom_response = [
                'success' => true,
                'message' => 'Your transaction was successful.'
            ];
            return response()->json($custom_response, 200);


        }elseif ($responseCode == '702' || $responseCode == 702)
        {
            //Customer not found

            //sender transaction details
            $sender_transaction_details = ConsumerTransaction::create([
                "consumer_id" => $sender_details->id,
                "consumer_name" => $sender_details->name,
                "payment_reference_number" => $ref_number,
                "type" => "sent", //send,deposit,transfer,pay
                "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                "partner_type" => $mno, //Wallet, Business,Agent,third-party name
                "amount" => $amount,
                "status" => 0, //failed
                "channel" => "GeePay",
                "custom_message" => "Sent to Mobile Money",
                "phone_number" => $sender_details->phone_number
            ]);

            $sender_transaction_details->save();

            //Insufficient credit
            $custom_response = [
                'success' => false,
                'message' => 'Your transaction has failed.'
            ];
            return response()->json($custom_response, 400);
        }elseif($responseCode == '104'  || $responseCode == 104)
        {
            //transaction id duplication

            //sender transaction details
            $sender_transaction_details = ConsumerTransaction::create([
                "consumer_id" => $sender_details->id,
                "consumer_name" => $sender_details->name,
                "payment_reference_number" => $ref_number,
                "type" => "sent", //send,deposit,transfer,pay
                "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                "partner_type" => $mno, //Wallet, Business,Agent,third-party name
                "amount" => $amount,
                "status" => 0, //failed
                "channel" => "GeePay",
                "custom_message" => "Sent to Mobile Money",
                "phone_number" => $sender_details->phone_number
            ]);

            $sender_transaction_details->save();

            //Insufficient credit
            $custom_response = [
                'success' => false,
                'message' => 'Your transaction has failed.'
            ];
            return response()->json($custom_response, 400);
        }

    }

    function konse_konse_send_mobile_money($amount, $phone_number, $ref_number, $sender_details, $mno, $sender_current_balance)
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
            //success
            $paymentID = (string)$xml->xpath('//env:Envelope/env:Body/ns2:processCustomerPaymentResponse/return/paymentID')[0];

            //receiver message body
            $message = "You have received the sum of ZMW" . $amount . " from " . $sender_details->phone_number . " " . $sender_details->name . ". fom GeePay App. Txn Id " . $ref_number;

            //send receiver sms notification
            $this->sendOntechSmsNotification($message, $phone_number);

            //sender transaction details
            $sender_transaction_details = ConsumerTransaction::create([
                "consumer_id" => $sender_details->id,
                "consumer_name" => $sender_details->name,
                "payment_reference_number" => $ref_number,
                "type" => "sent", //send,deposit,transfer,pay
                "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                "partner_type" => $mno, //Wallet, Business,Agent,third-party name
                "amount" => $amount,
                "status" => 2, //success
                "channel" => "GeePay",
                "custom_message" => "Sent to Mobile Money",
                "phone_number" => $sender_details->phone_number
            ]);

            $sender_transaction_details->save();

            //set sender current balance
            $new_sender_balance = $sender_current_balance - $amount;


            //new sender balance update
            $sender_balance_update = ConsumerBalance::where('consumer_id', $sender_details->id)->update([
                "balance" => $new_sender_balance
            ]);

            //receiver message body
            $sender_message = "You have sent  ZMW" . $amount . " to " . $phone_number . " . Your GeePay Balance is ZMW" . number_format($new_sender_balance, 2) . " Txn Id " . $ref_number;

            //send receiver sms notification
            $this->sendOntechSmsNotification($sender_message, $sender_details->phone_number);

            //Insufficient credit
            $custom_response = [
                'success' => true,
                'message' => 'Your transaction was successful.'
            ];
            return response()->json($custom_response, 200);


        }elseif ($responseCode == '702' || $responseCode == 702)
        {
            //Customer not found

            //sender transaction details
            $sender_transaction_details = ConsumerTransaction::create([
                "consumer_id" => $sender_details->id,
                "consumer_name" => $sender_details->name,
                "payment_reference_number" => $ref_number,
                "type" => "sent", //send,deposit,transfer,pay
                "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                "partner_type" => $mno, //Wallet, Business,Agent,third-party name
                "amount" => $amount,
                "status" => 0, //failed
                "channel" => "GeePay",
                "custom_message" => "Sent to Mobile Money",
                "phone_number" => $sender_details->phone_number
            ]);

            $sender_transaction_details->save();

            //Insufficient credit
            $custom_response = [
                'success' => false,
                'message' => 'Your transaction has failed.'
            ];
            return response()->json($custom_response, 400);
        }elseif($responseCode == '104'  || $responseCode == 104)
        {
            //transaction id duplication

            //sender transaction details
            $sender_transaction_details = ConsumerTransaction::create([
                "consumer_id" => $sender_details->id,
                "consumer_name" => $sender_details->name,
                "payment_reference_number" => $ref_number,
                "type" => "sent", //send,deposit,transfer,pay
                "partner" => $phone_number, //phone number, business_id, Agent_id, third-party name
                "partner_type" => $mno, //Wallet, Business,Agent,third-party name
                "amount" => $amount,
                "status" => 0, //failed
                "channel" => "GeePay",
                "custom_message" => "Sent to Mobile Money",
                "phone_number" => $sender_details->phone_number
            ]);

            $sender_transaction_details->save();

            //Insufficient credit
            $custom_response = [
                'success' => false,
                'message' => 'Your transaction has failed.'
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

    public function transaction_charge(Request $request)
    {
        $request->validate([
            "amount" => 'required',
            "category" => "required"
        ]);

        $charge_details = PaymentCommission::where("category", $request->category)->first();

        $convenience_fee = (((floatval($charge_details->cgrate_percentage) + floatval($charge_details->geepay_percentage))/100) * floatval($request->amount)) + (floatval($charge_details->cgrate_fixed_charge) + floatval($charge_details->geepay_fixed_charge));

        $total_amount = floatval($request->amount) + $convenience_fee;

        $custom_response = [
            "success" => true,
            "message" => "transaction calculations",
            "amount" => number_format($request->amount, 2),
            "convenience_fee" => number_format($convenience_fee,2),
            "total" => number_format($total_amount,2)
        ];

        return response()->json($custom_response, 200);

    }


    public function send_money(Request $request){
        $request->validate([
            'amount'=>'required'
        ]);

        $sender_details = Consumer::where('id',Auth::user()->id)->first();

        //Check which payment channel has been selected
        if($request->channel=='GeePay'){
            //check if the reciepient is registered on Geepay

            $sender_current_balance = ConsumerBalance::where('consumer_id',Auth::user()->id)->first()->balance;
            if($sender_current_balance >= $request->amount){
                if(Consumer::where('phone_number',$request->phone_number)->count()>0){
                    $payment_transaction_reference_number = $this->generatePaymentReferenceNumber();
                    //Customer registered on GeePay
                    //receiver details
                    $receiver_details = Consumer::where('phone_number',$request->phone_number)->first();

                    //get receiver current balance
                    $receiver_current_balance = ConsumerBalance::where('consumer_id',$receiver_details->id)->first()->balance;

                    //new_receiver_balance
                    $new_receiver_balance = $receiver_current_balance + $request->amount;
                    //receiver transaction details
                    $receiver_transaction_details = ConsumerTransaction::create([
                        "consumer_id" => $receiver_details->id,
                        "consumer_name" => $receiver_details->name,
                        "payment_reference_number" => $payment_transaction_reference_number,
                        "type" => "received", //send,deposit,transfer,pay
                        "partner" => $sender_details->phone_number,//phone number, business_id, Agent_id, third-party name
                        "partner_type" => "wallet",//Wallet, Business,Agent,third-party name
                        "amount" => $request->amount,
                        "status" => 2, //success
                        "channel" => "GeePay"
                    ]);

                    $receiver_transaction_details->save();

                    //new receiver balance update
                    $new_receiver_balance_update = ConsumerBalance::where('consumer_id',$receiver_details->id)->update([
                        "balance" => $new_receiver_balance
                    ]);

                    //receiver message body
                    $url_encoded_message = urlencode("You have received the sum of ZMW".$request->amount." from ".$sender_details->phone_number." ".$sender_details->name.". Your GeePay Balance is ZMW".number_format($new_receiver_balance,2)." Txn Id ".$payment_transaction_reference_number);

                    //send receiver sms notification
                    $sendReceiverSMS = Http::withoutVerifying()
                        ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $request->phone_number . '&api_key=121231313213123123');

                    //set sender current balance
                    $new_sender_balance = $sender_current_balance - $request->amount;

                    //sender transaction details
                    $sender_transaction_details = ConsumerTransaction::create([
                        "consumer_id" => $sender_details->id,
                        "consumer_name" => $sender_details->name,
                        "payment_reference_number" => $payment_transaction_reference_number,
                        "type" => "sent", //send,deposit,transfer,pay
                        "partner" => $receiver_details->phone_number,//phone number, business_id, Agent_id, third-party name
                        "partner_type" => "wallet",//Wallet, Business,Agent,third-party name
                        "amount" => $request->amount,
                        "status" => 2, //success
                        "channel" => "GeePay"
                    ]);

                    $sender_transaction_details->save();

                    //new sender balance update
                    $new_sender_balance_update = ConsumerBalance::where('consumer_id',$sender_details->id)->update([
                        "balance" => $new_sender_balance
                    ]);

                    //receiver message body
                    $url_encoded_message = urlencode("You have sent  ZMW".$request->amount." to ".$receiver_details->phone_number." ".$receiver_details->name.". Your GeePay Balance is ZMW".number_format($new_sender_balance,2)." Txn Id ".$payment_transaction_reference_number);

                    //send receiver sms notification
                    $sendSenderSMS = Http::withoutVerifying()
                        ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=' . $sender_details->phone_number . '&api_key=121231313213123123');


                    //Insufficient credit
                    $custom_response = [
                        'success'=>true,
                        'message'=>'Your transaction was successful.'
                    ];
                    return response()->json($custom_response,200);
                }else{
                    //Send an SMS to the recipient to download the Geepay app and create an Account
                    //send sms notification to business client's customer
                    //send confirmation sms
                    $url_encoded_message = urlencode("You have received the sum of K".$request->amount." from ".$sender_details->name.". To withdraw the money, download the GeePay App on https://geepay.co.zm or dial *776#. Txn:".$request->txn_number);

                    $sendSMS = Http::withoutVerifying()
                        ->post('https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay&phone=260' . $request->phone_number . '&api_key=121231313213123123');

                    //Store the money in the database

                }
            }else{
                //Insufficient credit
                $custom_response = [
                    'success'=>false,
                    'message'=>'You have insufficient balance to perform this transaction.'
                ];
                return response()->json($custom_response,400);
            }

        }elseif ($request->channel=='MNO'){
            //Process sending to MNOs with a commission charge

            //Check if the available balance is enough for the transaction plus commission
            $current_balance = ConsumerBalance::where('consumer_id',Auth::user()->id)->first()->balance;
            $send_money_commission = ConsumerCommissionStructure::where('type','send_to_mno')->first()->calculations;

            //Calculate Commission Value
            $calculated_commission = $request->amount * $send_money_commission;
            $total_amount_charged = $calculated_commission + $request->amount;

            if($current_balance >= $total_amount_charged){
                //proceed to check mno selected
                if($request->type=='Airtel Money'){
                    //Process Airtel payment

                }elseif ($request->type=='MTN Money'){
                    //Process MTN payment

                }elseif ($request->type=='Zamtel Money'){
                    //Process Zamtel payment

                }else{
                    $custom_response = [
                        'success'=>false,
                        'message'=>'You have have entered an invalid number.'
                    ];
                    return response()->json($custom_response,400);
                }
            }else{
                //Insufficient credit
                $custom_response = [
                    'success'=>false,
                    'message'=>'You have insufficient balance to perform this transaction.'
                ];
                return response()->json($custom_response,400);
            }
        }elseif ($request->channel=='Bank'){

        }else{
            //Invalid Option
            $custom_response = [
                'success'=>false,
                'message'=>'You have selected an invalid option.'
            ];
            return response()->json($custom_response,400);
        }

        //Check if the phone number is registered with GeePay

        //Check if balance is enough for the transaction

    }


    public function index()
    {
        //
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
    public function show(ConsumerTransaction $consumerTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsumerTransaction $consumerTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsumerTransaction $consumerTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsumerTransaction $consumerTransaction)
    {
        //
    }
}
