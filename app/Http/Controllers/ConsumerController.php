<?php

namespace App\Http\Controllers;

use App\Models\ConsumerBalance;
use App\Models\ConsumerTransaction;
use App\Models\District;
use App\Models\NRCDetail;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Consumer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ConsumerController extends Controller
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

    //generate four digit otp
    public function generateOTP()
    {
        $otp = mt_rand(1000, 9999);
        return $otp;
    }

    public function register_user(Request $request){
        $request->validate([
            "phone_number"=>"required",
            "name"=>"required",
            "password"=>"required"
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
            //$otp = $this->generateOTP();
            $otp = "1234";
            $qr_code = $this->generateQrCodeReferenceNumber();

            //saving new user into the database
            $user=Consumer::create([
                "avatar" => secure_asset('imgz/blank_profile_pic.png'),
                "name"=>$request->name,
                "phone_number"=>$request->phone_number,
                "password"=>Hash::make($request->password),
                "otp" => Hash::make("1234"),
                "gender" => $request->gender,
                "nrc_number" => $request->nrc_number,
                "dob" => $request->dob,
                "qr_code" => $qr_code,
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
                    "consumer_id" => $user->id,
                    "balance" => "0"
                ]);

                $new_balance->save();
            }

            //check if user is saved in the database
            if($user){
                //generate access token for user
                $token=$user->createToken('geepay_consumer')->plainTextToken;
                //send otp to user phone number

                $message = "Your GeePay wallet OTP is ".$otp.". Verify your account now";
                $this->sendOntechSmsNotification($message,$request->phone_number);

                //make custom response
                $response=[
                    "success"=>true,
                    "message"=>"Otp sent to your phone ".$request->phone_number,
                    "token" => $token
                ];
                //return custom response
                return response()->json($response,201);
            }
        }
    }

    public function consumer_data()
    {
        if(Consumer::where('id', Auth::user()->id)->count() > 0){
            $user = Consumer::where('id', Auth::user()->id)->first();

            $token = $user->createToken('geepay_consumer')->plainTextToken;
            //custom response
            $response = [
                "success" => true,
                "message" => "User logged in successfully",
                "id" => $user->id,
                "name" => $user->name,
                "avatar" => $user->avatar,
                "phone_number" => $user->phone_number,
                "token" => $token,
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

    public function avatar(Request $request){
        if ($request->file('image')) {
            //Saving new record

            $image = $request->file('image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/profiles');
            $img = Image::make($image);
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $input['imagename']);

            $destinationPath = public_path('/images');
            $image->move($destinationPath, $input['imagename']);

            $nrc_profile = Consumer::where('id', Auth::user()->id)->update([
                "avatar" => secure_asset('/images') . "/" . $input['imagename']
            ]);

            $user = Consumer::where('id', Auth::user()->id)->first();

            $token=$user->createToken('geepay_consumer')->plainTextToken;

            $response =[
                "success" => true,
                "message" => "image uploaded successfully",
                "token" => $token
            ];

            return response()->json($response,200);
        }
    }

    public function verify_otp(Request $request)
    {
        $request->validate([
            "otp" => "required",
        ]);

        $user = Consumer::where('id', Auth::user()->id)->first();
        if(Hash::check($request->otp,$user->otp))
        {
            //generate access token for user
            $token=$user->createToken('geepay_consumer')->plainTextToken;

            //nullify otp
            $nullify_otp = Consumer::where('id',$user->id)->update([
                "otp" => "null"
            ]);
            //make custom response
            $response=[
                "success"=>true,
                "message"=>"User Registered Successfully",
                "id"=>$user->id,
                "name"=>$user->name,
                "token"=>$token,
                "phone_number"=>$user->phone_number,
                "avatar" => $user->avatar
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

    public function login(Request $request)
    {
        //validate input data
        $request->validate([
            "phone_number" => "required",
            "password" => "required"
        ]);

        //check is user is registered already
        if($registered_user = Consumer::where('phone_number',$request->phone_number)->count() > 0){
            $user = Consumer::where('phone_number',$request->phone_number)->first();
            if(Hash::check($request->password, $user->password))
            {
                $token = $user->createToken('geepay_consumer')->plainTextToken;
                //custom response
                $response = [
                    "success" => true,
                    "message" => "User logged in successfully",
                    "id" => $user->id,
                    "name" => $user->name,
                    "avatar" => $user->avatar,
                    "phone_number" => $user->phone_number,
                    "token" => $token,
                    "qr_code" => $user->qr_code,
                    "is_active" => $user->is_active
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

    public function get_account_details(Request $request){
        $request->validate([
            "phone_number" => "required"
        ]);

        if(Consumer::where('phone_number',$request->phone_number)->count()>0){
            $receiver = Consumer::where('phone_number',$request->phone_number)->first();

            $custom_response = [
                "success" => true,
                "message" => "Receiver account found",
                "name" => $receiver->name,
                "avatar" => $receiver->avatar,
                "phone_number" => $receiver->phone_number
            ];


            return response()->json($custom_response, 200);
        }else{
            $custom_response = [
                "success" => false,
                "message" => "Receiver not yet registered on GeePay"
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function consumer_nrc_scans(Request $request)
    {

        if($request->file('nrc_front') && $request->file('nrc_back')){
            if(Consumer::where('id', Auth::user()->id)->count() > 0){
                //save nrc records  and
                $frontName = Auth::user()->id.'_front_' . time() . '.' . $request->file('nrc_front')->getClientOriginalExtension();
                $backName = Auth::user()->id.'_back_' . time() . '.' . $request->file('nrc_back')->getClientOriginalExtension();

                $front_path = $request->file('nrc_front')->storeAs('public/nrc', $frontName);
                $back_path = $request->file('nrc_back')->storeAs('public/nrc', $backName);

                $update_consumer = Consumer::where('id', Auth::user()->id)->update([
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

    public function consumer_avatar(Request $request)
    {
        if($request->file('image')){
            if(Consumer::where('id', Auth::user()->id)->count() > 0){
                //save nrc records  and
                $imageName = Auth::user()->id.'_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

                $imagePath = $request->file('image')->storeAs('public/profile_pics', $imageName);

                $update_consumer = Consumer::where('id', Auth::user()->id)->update([
                    "avatar" => 'profile_pics/'.$imageName
                ]);

                $response = [
                    "success" => true,
                    "message" => "Avatar uploaded successfully",
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

    public function consumer_selfie(Request $request)
    {
        if($request->file('selfie')){
            if(Consumer::where('id', Auth::user()->id)->count() > 0){
                // Generate a unique filename for the uploaded file
                $imageName = Auth::user()->id.'_' . time() . '.' . $request->file('selfie')->getClientOriginalExtension();

                $imagePath = $request->file('selfie')->storeAs('public/selfie', $imageName);

                $update_consumer = Consumer::where('id', Auth::user()->id)->update([
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

    public function Provinces(){

        $provinces = Province::all();
        $custom_response = [
            "success" => true,
            "message" => "Provinces fetched successfully",
            "data" => $provinces
        ];
        //return custom response
        return response()->json($custom_response,200);

    }

    public function Districts($id)
    {
        $districts = District::where('province_id', $id)->orderBy('name', 'ASC')->get();
        $custom_response = [
            "success" => true,
            "message" => "Districts fetched successfully",
            "data" => $districts
        ];
        //return custom response
        return response()->json($custom_response,200);
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

    public function agent_nrc_upload(Request $request)
    {
        $request->validate([
            "nrc_number" => "required"
        ]);

        if ($request->file('frontNrc') && $request->file('backNrc')) {
            //Saving new record

            $image1 = $request->file('frontNrc');
            $input['imagename1'] = time() . '.' . $image1->getClientOriginalExtension();

            $image2 = $request->file('backNrc');
            $input['imagename2'] = time() . '.' . $image2->getClientOriginalExtension();

            $destinationPath = public_path('/nrc');
            $img = Image::make($image1);
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $input['imagename1']);

            $img2 = Image::make($image2);
            $img2->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $input['imagename2']);

            $image1->move($destinationPath, $input['imagename1']);

            $image2->move($destinationPath, $input['imagename2']);

            $nrc_profile = NRCDetail::create([
                "user_id" => Auth::user()->id,
                "nrc_passport_number" => $request->nrc_number,
                "nrc_passport_scan_front" => secure_asset('/nrc') . "/" . $input['imagename1'],
                "nrc_passport_scan_back" => secure_asset('/nrc') . "/" . $input['imagename2']
            ]);

            $nrc_profile->save();

            $user = Consumer::where('id', Auth::user()->id)->first();
            $token=$user->createToken('geepay_consumer')->plainTextToken;

            $response =[
                "success" => true,
                "message" => "image uploaded successfully",
                "id" => $user->id,
                "name" => $user->name,
                "avatar" => $user->avatar,
                "phone_number" => $user->phone_number,
                "token" => $token
            ];

            return response()->json($response,200);
        }
    }


}
