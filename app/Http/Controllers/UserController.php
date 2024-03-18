<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessType;
use App\Models\Client;
use App\Mail\VerfiyAccount;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    function generate_verify_string()
    {
        $namespace = Uuid::NAMESPACE_DNS;
        $name = 'app.geepay.fellow';
        return Uuid::uuid5($namespace, $name);
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "business_name" => "required",
            "business_email" => "required",
            "password" => "required"
        ]);

        //check if the client user already exists
        if(Client::where('email',$request->business_email)->count() > 0)
        {
            $custom_response = [
                "success" => false,
                "message" => "User already exist, please login"
            ];

            return response()->json($custom_response, 400);
        }else{
            //verify token
            $verify_token = $this->generate_verify_string();

            //register new client user
            $new_client = Client::create([
                "name" => $request->name,
                "phone_number" => '260'.$request->phone_number,
                "email" => $request->business_email,
                "verify_token" => $verify_token,
                "is_account_owner" => 1,
                "role_id" => 1,
                "password" => Hash::make($request->password)
            ]);

            $new_client->save();

            if($new_client)
            {
                //save new business details
                $new_business = Business::create([
                    "user_id" => $new_client->id,
                    "business_type_id" => "",
                    "province_id" => "",
                    "country_id" => 1,
                    "business_name" => $request->business_name,
                    "business_email" => $request->business_email
                ]);

                $new_business->save();

                if($new_business)
                {
                    //edit the client table record
                    $client_update = Client::where('id',$new_client->id)->update([
                        "business_id" => $new_business->id
                    ]);

                    //data to be sent via email
                    $email_data = [
                        "business_name" => $request->business_name,
                        "user_name" => $request->name,
                        "url" => "https://gpay.subilo.com/?".$verify_token
                    ];

                    //send verify email notification
                    Mail::to($request->business_email)->send(new VerfiyAccount($email_data));

                    $custom_response = [
                        "success" => true,
                        "message" => "Verify your email account",
                    ];

                    return response()->json($custom_response, 201);
                }
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        //check if the client user account exist
        if(Client::where('email',$request->email)->where('is_active', 1)->count() > 0)
        {
            $logging_user = Client::where('email',$request->email)->first();

            //check if user account is active
            if($logging_user->is_active == 1)
            {
                //check the password
                if(Hash::check($request->password, $logging_user->password))
                {
                    //generate access token
                    $token = $logging_user->createToken('gpay_office')->plainTextToken;
                    $business = Business::where('user_id', $logging_user->id)->first();

                    $province = null;
                    if(Province::where('id', $business->province_id)->count() > 0)
                    {
                        $province = Province::where('id', $business->province_id)->first()->value('name');
                    }

                    $country = null;

                    if(Country::where('id', $business->country_id)->count() > 0)
                    {
                        $country = Country::where('id', $business->country_id)->first()->value('name');
                    }

                    $business_category = null;


                    if(BusinessCategory::where('id', $business->business_category_id)->count() > 0)
                    {
                        $business_category = BusinessCategory::where('id', $business->business_category_id)->first()->value('name');
                    }



                    $custom_response = [
                        "success" => true,
                        "message" => "Successful login",
                        "id" => $logging_user->id,
                        "role_id" => $logging_user->role_id,
                        "name" => $logging_user->name,
                        "email" => $logging_user->email,
                        "avatar" => $logging_user->avatar,
                        "token" => $token,
                        "business_id" => $business->id,
                        "business_name" => $business->business_name,
                        "business_logo" => $business->business_logo,
                        "is_account_owner" => $logging_user->is_account_owner,
                        "business_address_line_1" => $business->business_address_line_1,
                        "business_province" => $province,
                        "business_country" => $country,
                        "business_category" => $business_category,
                        "business_type" => $business->business_type,
                        "business_phone_number" => $business->business_phone_number,
                        "business_bank_account_number" => $business->business_bank_account_number,
                        "business_bank_account_name" => $business->business_bank_account_name,
                        "business_bank_account_branch" => $business->business_bank_account_branch
                    ];

                    return response()->json($custom_response,200);
                }else{
                    $custom_response = [
                        "success" => false,
                        "message" => "You have entered wrong credentials",
                        "reason" => "Wrong Credentials"
                    ];

                    return response()->json($custom_response,401);
                }
            }else{
                //check if the account is verified
                if($logging_user->is_email_verified == 1)
                {
                    //respond to client to contact the admin for assistance
                    $custom_response = [
                        "success" => false,
                        "message" => "Kindly contact the support team for assistance",
                        "reason" => "Account Suspended"
                    ];

                    return response()->json($custom_response,401);
                }else{
                    //verify email token
                    $verify_email_token = $this->generate_verify_string();

                    //data to be sent via email
                    $email_data = [
                        "business_name" => Business::where('id',$logging_user->id)->first()->value('business_name'),
                        "user_name" => $logging_user->name,
                        "url" => "https://gpay.subilo.com/?".$verify_email_token
                    ];

                    //send verify email notification
                    Mail::to($logging_user->email)->send(new VerfiyAccount($email_data));

                    $custom_response = [
                        "success" => true,
                        "message" => "Verify your email account",
                    ];

                    return response()->json($custom_response, 200);

                }
            }

        }else{
            $custom_response = [
                "success" => false,
                "message" => "Account not registered/ Not Active",
                "reason" => "Account Not Exist"
            ];

            return response()->json($custom_response, 401);
        }
    }

    public function verify_account(Request $request)
    {
        //validate the input
        $request->validate([
            "token" => "required"
        ]);

        //check the token availability
        if(Client::where('verify_token', $request->token)->count() > 0)
        {
            $logging_user = Client::where('verify_token', $request->token)->first();
            $business = Business::where('user_id', $logging_user->id)->first();
            $token = $logging_user->createToken('gpay_office')->plainTextToken;


            //update the client record
            $update_client = Client::where('verify_token', $request->token)->update([
                "is_email_verified" => 1,
                "is_active" => 1
            ]);

            $custom_response = [
                "success" => true,
                "message" => "Successful login",
                "id" => $logging_user->id,
                "role_id" => $logging_user->role_id,
                "name" => $logging_user->name,
                "email" => $logging_user->email,
                "avatar" => $logging_user->avatar,
                "token" => $token,
                "business_name" => $business->business_name,
                "business_logo" => $business->business_logo,
                "is_account_owner" => $logging_user->is_account_owner,
                "business_address_line_1" => $business->business_address_line_1,
                "business_province" => Province::where('id', $business->province_id)->first()->value('name'),
                "business_country" => Country::where('id', $business->country_id)->first()->value('name'),
                "business_category" => BusinessCategory::where('id', $business->business_category_id)->first()->value('name'),
                "business_type" => BusinessType::where('id', $business->business_type_id)->first()->value('name'),
                "business_phone_number" => $business->business_phone_number,
                "business_bank_account_number" => $business->business_bank_account_number,
                "business_bank_account_name" => $business->business_bank_account_name,
                "business_bank_account_branch" => $business->business_bank_account_branch
            ];

            return response()->json($custom_response,200);
        }else{
            $custom_response = [
                "success" => false,
                "message" => "Invalid verify link",
                "reason" => "Invalid Token"
            ];

            return response()->json($custom_response, 401);
        }
    }
}
