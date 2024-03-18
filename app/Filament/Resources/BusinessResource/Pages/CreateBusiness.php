<?php

namespace App\Filament\Resources\BusinessResource\Pages;

use App\Filament\Resources\BusinessResource;
use App\Jobs\SendAccountMail;
use App\Models\AccumulativeBalance;
use App\Models\APICredential;
use App\Models\AuditTrail;
use App\Models\BankBranch;
use App\Models\BankName;
use App\Models\Business;
use App\Models\Client;
use App\Models\CurrentBalance;
use App\Models\Deposit;
use App\Models\FrontEndUrl;
use App\Models\Webhook;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use function App\Filament\Resources\checkCreateBusinessesPermission;


function apiAccessSecretId() {
    $secret_id = Uuid::uuid4();
    // Check if the secret id already exists in the database
    if (DB::table('a_p_i_credentials')->where('secret_id', $secret_id)->exists()) {
        // If the secret id already exists, generate a new one recursively
        return apiAccessSecretId();
    }
    return $secret_id;
}

function apiAccessToken() {
    $access_token = Uuid::uuid4();
    // Check if the secret id already exists in the database
    if (DB::table('a_p_i_credentials')->where('access_token', $access_token)->exists()) {
        // If the secret id already exists, generate a new one recursively
        return apiAccessToken();
    }
    return $access_token;
}

function sendSmsNotification(string $message, string $phone_number): void

{
    // Send confirmation SMS
    $url_encoded_message = urlencode($message);

    $url = 'https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay Biz&phone=' . $phone_number . '&api_key=121231313213123123';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Use this only if you have SSL verification issues
    $response = curl_exec($ch);
    curl_close($ch);
}


function generateAccNumber()
{
    $prefix = 'GP'; // Prefix for the account number
    $suffix = time(); // Suffix for the account number (UNIX timestamp)

    // Generate a random number between 1000 and 9999
    $random = rand(100000000000, 999999999999);

    // Combine the prefix, random number, and suffix to form the account number
    $account_number = $prefix . $random;

    // Check if the payment reference number already exists in the database
    if (DB::table('businesses')->where('account_number',$account_number)->exists()) {
        // If the payment reference number already exists, generate a new one recursively
        return generateAccNumber();
    }
    return $account_number;
}

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;


    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateBusinessesPermission(), 403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Businesses",
            "activity" => "Viewed Create Businesses Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $formatted_business_name = strtolower(str_replace(' ', '', $data['business_name']));

        //get frontend url
        $frontend_url = FrontEndUrl::first()->domain;

        //branch code
        $bank_id = BankName::where('name', $data['business_bank_name'])->first()->id;
        $branch_code = BankBranch::where('bank_name_id', $bank_id)->first()->branch_code ?? "";

        //the shortened url
        $checkout_share_url = $frontend_url . '/pay/' .$formatted_business_name;
        $account_number = generateAccNumber();
        $user_id = Auth::user()->id;
        $business_logo = 'business_logo_placeholder.png';

        $data['account_number'] = $account_number;
        $data['user_id'] = $user_id;
        $data['business_logo'] = $business_logo;
        $data['payment_checkout'] = $checkout_share_url;
        $data['is_active'] = 0;
        $data['business_bank_account_branch_code'] = $branch_code;

        return $data;

    }

    protected function afterCreate()
    {
        //log user activity
        $activity = AuditTrail::create([
            "user_id" => Auth::user()->id,
            "module" => "Businesses",
            "activity" => "Created Business record with ID ".$this->record,
            "ip_address" => request()->ip()
        ]);

        $activity->save();

        $password = "New.1234";
        //create client record
        $new_client = Client::create([
            "name" => $this->data['account_owner_name'],
            "email" => $this->data['business_email'],
            "password" => Hash::make($password),
            "is_account_owner" => 1,
            "phone_number" => $this->data['business_phone_number'],
            "role_id" => 2, //2 Admin 3 Manager 4 Viewer
            "business_id" => $this->record->id,
            "is_active" => 0
        ]);

        $new_client->save();

        $update_business = Business::where('id', $this->record->id)->update([
            "user_id" => $new_client->id
        ]);

        $demo_secret_id = "geepay".$this->record->id;
        $demo_access_token = apiAccessToken();

        //production
        $api_secret_id =  apiAccessSecretId();
        $api_access_token = apiAccessToken();

        $production_api_credentials = APICredential::create([
            "business_id" => $this->record->id,
            "secret_id" => $api_secret_id,
            "access_token" => Hash::make($api_access_token),
            "environment" => "production",
            "reset_count" => 0
        ]);

        $production_api_credentials->save();

        $demo_api_credentials = APICredential::create([
            "business_id" => $this->record->id,
            "secret_id" => $demo_secret_id,
            "access_token" => Hash::make($demo_access_token),
            "environment" => "demo",
            "reset_count" => 0
        ]);

        $demo_api_credentials->save();

        if(!empty($this->record->callback_url !== "" && $this->record->callback_url !== null))   {
            // save webhook call back url
            $webhook = Webhook::create([
                "business_id" => $this->record->id,
                "callback_url" => $this->data['callback_url'],
            ]);

            $webhook->save();
        }

        //save the deposit
        $new_deposit_balance = Deposit::create([
            "business_id" => $this->record->id,
            "account_number" => $this->record->account_number,
            "business_name" => $this->data['business_name'],
            "old_balance" => "0",
            "new_balance" => "0",
            'is_deleted' => 0
        ]);

        $new_deposit_balance->save();

        //save the current balance
        $new_current_balance = CurrentBalance::create([
            "business_id" => $this->record->id,
            "disbursement" => "0",
            "payments" => "0",
            "payouts" => "0"
        ]);

        $new_current_balance->save();

        //save the accumulative balance
        $new_accumulative_balance = AccumulativeBalance::create([
            "business_id" => $this->record->id,
            "disbursement" => "0",
            "payments" => "0",
            "payouts" => "0"
        ]);

        $new_accumulative_balance->save();

        $candidate_name = $this->data['account_owner_name'];
        $exploded_string  = explode(" ", $candidate_name);

        //send email with credentials to the business email address
        $account_number_to_send = $this->record->account_number;
        $account_owner_to_send = $exploded_string[0];
        $api_secret_id_to_send = $api_secret_id;
        $api_access_token_to_send = $api_access_token;

        $message = "Your GeePay business account number ".$this->record->account_number." has been created successfully. Use ".$password." as your temporal password to login after account activation";

        sendSmsNotification($message, "260".$this->data['business_phone_number']);

        //SendAccountMail::dispatch($account_number_to_send,$account_owner_to_send,$api_secret_id_to_send,$api_access_token_to_send,$password,$this->data['business_email']);


    }

}
