<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Consumer;
use App\Models\ConsumerBalance;
use App\Models\ConsumerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    function generateQrCodeReferenceNumber() {
        $prefix = 'QR'; // Prefix for the account number
        $suffix = time(); // Suffix for the account number (UNIX timestamp)

        // Generate a random number between 1000 and 9999
        $random = rand(1000, 9999);

        // Combine the prefix, random number, and suffix to form the account number
        $raw_qr_code_reference_number = $prefix . $random . $suffix;

        $qr_code_reference_number = substr($raw_qr_code_reference_number, 0, 16);

        // Check if the payment reference number already exists in the database
        if (DB::table('consumers')->where('qr_code',  $qr_code_reference_number)->exists()) {
            // If the payment reference number already exists, generate a new one recursively
            return $this->generateQrCodeReferenceNumber();
        }

        return $qr_code_reference_number;
    }

    public function generateOTP()
    {
        $otp = mt_rand(1000, 9999);
        return $otp;
    }

    public function generateTemporalPin()
    {
        $otp = mt_rand(1000, 9999);
        return $otp;
    }

    public function register_agent(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required"
        ]);

        if(Agent::where("email", $request->email)->count() > 0)
        {
            //email taken
            $custom_response = [
                "success" => false,
                "message" => "email address is already registered"
            ];

            return response()->json($custom_response, 400);
        }elseif(Agent::where("phone_number", $request->phone_number)->count() > 0)
        {
            //phone number taken
            $custom_response = [
                "success" => false,
                "message" => "phone number is already registered"
            ];

            return response()->json($custom_response, 400);
        }else{
            //register new agent
            if($request->file('image')){
                //image submitted

                $path = Storage::putFile('image', $request->image);

                $new_agent = Agent::create([
                    "name" => $request->name,
                    "email" => $request->email,
                    "phone_number" => $request->phone_number,
                    "password" => Hash::make($request->password),
                    "image" => $path,
                    "nrc_number" => $request->nrc_number,
                ]);

                $custom_response = [
                    "success" => true,
                    "message" => "new agent registered successfully"
                ];

                return response()->json($custom_response, 201);

            }else{
                $new_agent = Agent::create([
                    "name" => $request->name,
                    "email" => $request->email,
                    "phone_number" => $request->phone_number,
                    "password" => Hash::make($request->password),
                    "nrc_number" => $request->nrc_number,
                ]);

                $custom_response = [
                    "success" => true,
                    "message" => "new agent registered successfully"
                ];

                return response()->json($custom_response, 201);
            }
        }
    }

    public function login_agent(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        //check is user is registered already
        if(Agent::where('email',$request->email)->where('is_active',1)->count() > 0){
            $agent = Agent::where('email',$request->email)->first();
            if(Hash::check($request->password, $agent->password))
            {
                $token = $agent->createToken('geepay_agent')->plainTextToken;
                //custom response
                $response = [
                    "success" => true,
                    "message" => "Agent logged in successfully",
                    "id" => $agent->id,
                    "name" => $agent->name,
                    "image" => $agent->image,
                    "phone_number" => $agent->phone_number,
                    "token" => $token
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
        }else{
            //custom response
            $response = [
                "success" => false,
                "message" => "User not found",
            ];

            return response()->json($response,404);
        }
    }

    public function agent_register_consumer(Request $request){
        $request->validate([
            "phone_number"=>"required",
            "name"=>"required",
            "nrc_number" => "required",
            "gender" => "required"
        ]);
        //check if user with the phone number exists
        $registered_user=Consumer::where('phone_number',$request->phone_number)->whereNotNull('phone_number')->exists();//returns true or false
        //custom response
        if ($registered_user){
            $response=[
                "success"=>false,
                "message"=>"User Already Registered"
            ];
            //return custom response
            return response()->json($response,400);
        }else{
            $otp = $this->generateOTP();
            $qr_code = $this->generateQrCodeReferenceNumber();
            //saving new user into the database
            $user=Consumer::create([
                "agent_id" => Auth::user()->id,
                "name"=>$request->name,
                "phone_number"=>$request->phone_number,
                "otp" => Hash::make($otp),
                "gender" => $request->gender,
                "nrc_number" => $request->nrc_number,
                "qr_code" => $qr_code,
                "dob" => $request->dob,
                "is_active" => 0
            ]);
            $user->save();

            if(ConsumerTransaction::where("phone_number", $request->phone_number)->where("lead", 1)->count() > 0){
                $current_balance = ConsumerTransaction::where("phone_number", $request->phone_number)->where("lead", 1)->sum("amount");

                //updates transaction records
                $update_transaction_records = ConsumerTransaction::where("phone_number", $request->phone_number)->where("lead", 1)->update([
                    "consumer_id" => $user->id,
                    "consumer_name" => $request->name,
                    "lead" => 0
                ]);

                //New Consumer Balance
                $new_balance = ConsumerBalance::create([
                    "consumer_id"=>$user->id,
                    "balance"=> $current_balance
                ]);

            }else{
                //New Consumer Balance
                $new_balance = ConsumerBalance::create([
                    "consumer_id"=>$user->id,
                    "balance"=> "0"
                ]);

                $new_balance->save();
            }


            //check if user is saved in the database
            if($user){

                $message = "Your One Time Pin is ".$otp;
                $this->sendSmsNotification($message, $request->phone_number);

                //make custom response
                $response=[
                    "success" => true,
                    "message" => "Otp sent to your phone ".$request->phone_number,
                    "consumer_id" => $user->id,
                    "consumer_name" => $user->name,
                    "consumer_phone_number" => $user->phone_number
                ];

                //return custom response
                return response()->json($response,201);
            }
        }
    }

    public function agent_kyc_verify_otp(Request $request)
    {
        $request->validate([
            "consumer_id" => "required",
            "otp" => "required",
        ]);

        $user = Consumer::where('id', $request->consumer_id)->first();
        if(Hash::check($request->otp,$user->otp))
        {
            //nullify otp
            $nullify_otp = Consumer::where('id',$request->consumer_id)->update([
                "otp" => "null"
            ]);


            //make custom response
            $response=[
                "success"=>true,
                "message"=>"User OTP validated Successfully. Proceed to next step"
            ];

            //return custom response
            return response()->json($response,200);
        }else{
            $response=[
                "success"=>false,
                "message"=>"Oops, wrong otp entered",
            ];
            //return custom response
            return response()->json($response,400);
        }
    }

    public function agent_kyc_nrc_scans(Request $request)
    {
        $request->validate([
            "consumer_id" => "required"
        ]);

        if($request->file('nrc_front') && $request->file('nrc_back')){
            if(Consumer::where('id', $request->consumer_id)->count() > 0){
                //save nrc records  and
                $frontName = $request->consumer_id.'_front_' . time() . '.' . $request->file('nrc_front')->getClientOriginalExtension();
                $backName = $request->consumer_id.'_back_' . time() . '.' . $request->file('nrc_back')->getClientOriginalExtension();

                $front_path = $request->file('nrc_front')->storeAs('public/nrc', $frontName);
                $back_path = $request->file('nrc_back')->storeAs('public/nrc', $backName);

                $update_consumer = Consumer::where('id', $request->consumer_id)->update([
                    "nrc_front" => 'nrc/'.$frontName,
                    "nrc_back" => 'nrc/'.$backName,
                ]);

                $response = [
                    "success" => true,
                    "message" => "NRC Scans uploaded successfully",
                ];

                return response()->json($response,200);

            }else{
                $response = [
                    "success" => false,
                    "message" => "No record",
                ];

                return response()->json($response,400);
            }
        }else{
            $response = [
                "success" => false,
                "message" => "Invalid request",
            ];

            return response()->json($response,400);
        }
    }

    public function agent_kyc_avatar(Request $request)
    {
        $request->validate([
            "consumer_id" => "required"
        ]);

        if($request->file('image')){
            if(Consumer::where('id', $request->consumer_id)->where('agent_id', Auth::user()->id)->count() > 0){
                //save nrc records  and
                $imageName = $request->consumer_id.'_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

                $imagePath = $request->file('image')->storeAs('public/profile_pics', $imageName);

                $update_consumer = Consumer::where('id', $request->consumer_id)->update([
                    "avatar" => 'profile_pics/'.$imageName
                ]);

                Log::info("Avatar uploaded successfully");

                $response = [
                    "success" => true,
                    "message" => "Avatar uploaded successfully",
                ];

                return response()->json($response,200);

            }else{
                $response = [
                    "success" => false,
                    "message" => "Agent not authorised to edit this",
                ];

                return response()->json($response,400);
            }



        }else{
            $response = [
                "success" => false,
                "message" => "Invalid request",
            ];

            return response()->json($response,400);
        }
    }

    public function agent_kyc_records()
    {
        $records = Consumer::where("agent_id", Auth::user()->id)->get();

        //custom response
        $response = [
            "success" => true,
            "message" => "records fetched successfully",
            "data" => $records
        ];

        return response()->json($response,200);


    }

    public function agent_kyc_consumer_data(Request $request)
    {
        $request->validate([
            "consumer_id" => "required"
        ]);

        if(Consumer::where('id', $request->consumer_id)->count() > 0){
            $user = Consumer::where('id', $request->consumer_id)->first();

            //custom response
            $response = [
                "success" => true,
                "message" => "User registered successfully",
                "id" => $user->id,
                "name" => $user->name,
                "avatar" => $user->avatar,
                "phone_number" => $user->phone_number,
                "qr_code" => $user->qr_code,
                "is_active" => $user->is_active
            ];

            return response()->json($response,200);
        }else{
            $response = [
                "success" => false,
                "message" => "User not found",
            ];

            return response()->json($response,400);
        }

    }

    public function agent_kyc_selfie(Request $request)
    {
        if($request->file('selfie')){
            if(Consumer::where('id', $request->consumer_id)->where('agent_id', Auth::user()->id)->count() > 0){
                // Generate a unique filename for the uploaded file
                $imageName = $request->consumer_id.'_' . time() . '.' . $request->file('selfie')->getClientOriginalExtension();

                $imagePath = $request->file('selfie')->storeAs('public/selfie', $imageName);

                $update_consumer = Consumer::where('id', $request->consumer_id)->update([
                    "selfie" => 'selfie/'.$imageName
                ]);

                $response = [
                    "success" => true,
                    "message" => "Selfie uploaded successfully",
                ];

                return response()->json($response,200);

            }else{
                $response = [
                    "success" => false,
                    "message" => "No record",
                ];

                return response()->json($response,400);
            }
        }else{
            $response = [
                "success" => false,
                "message" => "Invalid request",
            ];

            return response()->json($response,400);
        }
    }

    public function agent_kyc_opt_resend(Request $request)
    {
        $request->validate([
            "consumer_id" => "required"
        ]);

        $otp = $this->generateOTP();

        $message = "Your OTP is ".$otp;
        $this->sendSmsNotification($message, $request->phone_number);

        $update_record = Consumer::where("id", $request->consumer_id)->updte([
            "otp" => Hash::make($otp),
        ]);

        //make custom response
        $response=[
            "success" => true,
            "message" => "Otp sent to your phone ".$request->phone_number,
            "consumer_id" => $request->consumer_id,
        ];

        //return custom response
        return response()->json($response,200);

    }

    function sendSmsNotification(string $message, string $phone_number): void
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
}
