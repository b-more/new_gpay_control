<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v2')->group( function () {
    Route::post('/receipt', [App\Http\Controllers\PaymentController::class,'show_receipt_api']);

    Route::prefix('m')->group(function (){
        Route::post('/login',[App\Http\Controllers\ConsumerController::class,'login']);
        Route::post('/register',[App\Http\Controllers\ConsumerController::class,'register_user']);
        Route::post('/otp',[App\Http\Controllers\ConsumerController::class,'verify_otp']);
        Route::middleware('auth:sanctum')->group( function(){
            Route::post('/send',[App\Http\Controllers\ConsumerTransactionController::class,'send_money']);
        });

    });

    Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);

    Route::group(['middleware' => ['cors']], function(){
        Route::post('/pay',[App\Http\Controllers\PaymentController::class,'client_api_payment']);

        Route::get('/payments/excel',[App\Http\Controllers\PaymentController::class,'excel_export']);
        Route::post('/create/payment',[App\Http\Controllers\PaymentController::class,'create_payment']);
        Route::get('/all/payments',[App\Http\Controllers\PaymentController::class,'all_payments_api']);
        Route::post('/successful/payments',[App\Http\Controllers\PaymentController::class,'successful_payments_api']);
        Route::get('/pending/payments',[App\Http\Controllers\PaymentController::class,'pending_payments_api']);
        Route::get('/failed/payments',[App\Http\Controllers\PaymentController::class,'failed_payments_api']);
        Route::get('/refunded/payments',[App\Http\Controllers\PaymentController::class,'refunded_payments_api']);
        Route::post('/create/instant',[App\Http\Controllers\PaymentController::class,'instant_payment']);
        Route::post('/confirm/instant/payment',[App\Http\Controllers\PaymentController::class,'instant_payment_confirmation']);
        Route::post('/confirm/instant/payment/button',[App\Http\Controllers\PaymentController::class,'instant_payment_confirmation_manual']);
        Route::post('/confirm/instant/payment/deleted',[App\Http\Controllers\PaymentController::class,'instant_payment_confirmation_deleted']);
        Route::post('/delete/payment',[App\Http\Controllers\PaymentController::class,'destroy']);
        Route::post('/refund/payment',[App\Http\Controllers\RefundController::class,'store']);
        //Route::post('/receipt', [App\Http\Controllers\PaymentController::class,'show_receipt_api']);

        Route::get('/business/customers',[App\Http\Controllers\CustomerController::class,'api_business_customers']);
        Route::get('/business/customer',[App\Http\Controllers\CustomerController::class,'api_business_customer']);

        Route::get('/business/categories',[App\Http\Controllers\BusinessCategoryController::class,'index']);
        Route::get('/business/deposit/list',[App\Http\Controllers\BusinessCategoryController::class,'api_list']);

        Route::post('/business/disbursement/balance',[App\Http\Controllers\TransferController::class,'api_disbursement_balance']);

        Route::post('/transfer/mno',[App\Http\Controllers\TransferController::class,'api_send_mobile_money']);
        Route::get('/successful/transfers',[App\Http\Controllers\TransferController::class,'api_business_transfers']);
        Route::post('/delete/transfer',[App\Http\Controllers\TransferController::class,'destroy']);



        Route::middleware('auth:sanctum')->group( function(){
            Route::get('/business/dashboard', [App\Http\Controllers\BusinessController::class,'api_business_dashboard']);
            Route::get('/business/recent/payments',[App\Http\Controllers\BusinessController::class,'api_dashboard_recent_payments']);
            Route::get('/payments',[App\Http\Controllers\PaymentController::class,'business_payments']);
        });


    });

});
Route::prefix('v1/m')->group(function (){
    Route::post('/login',[App\Http\Controllers\ConsumerController::class,'login']);
    Route::post('/register',[App\Http\Controllers\ConsumerController::class,'register_user']);
    Route::post('/agent/register', [App\Http\Controllers\AgentController::class,'register_agent']);
    Route::post('/agent/login', [App\Http\Controllers\AgentController::class, 'login_agent']);

    Route::get('/provinces',[App\Http\Controllers\ConsumerController::class,'Provinces'])->name('provinces');
    Route::get('/districts/{id}',[App\Http\Controllers\ConsumerController::class,'Districts'])->name('districts');

    Route::middleware('auth:sanctum')->group( function(){
        Route::post('/user/data', [App\Http\Controllers\ConsumerController::class,'consumer_data']);
        Route::post('/consumer/avatar',[App\Http\Controllers\ConsumerController::class,'consumer_avatar']);
        Route::post('/consumer/nrc_scans',[App\Http\Controllers\ConsumerController::class,'consumer_nrc_scans']);
        Route::post('/consumer/selfie',[App\Http\Controllers\ConsumerController::class,'consumer_selfie']);
        Route::post('/agent/consumer/otp', [App\Http\Controllers\AgentController::class,'agent_kyc_verify_otp']);
        Route::post('/agent/consumer/resend/otp', [App\Http\Controllers\AgentController::class,'agent_kyc_opt_resend']);
        Route::post('/agent/consumer/avatar',[App\Http\Controllers\AgentController::class,'agent_kyc_avatar']);
        Route::post('/agent/consumer/register', [App\Http\Controllers\AgentController::class,'agent_register_consumer']);
        Route::post('/agent/consumer/nrc_scans', [App\Http\Controllers\AgentController::class, 'agent_kyc_nrc_scans']);
        Route::post('/agent/consumer/user/data', [App\Http\Controllers\AgentController::class, 'agent_kyc_consumer_data']);
        Route::post('/agent/consumer/records', [App\Http\Controllers\AgentController::class, 'agent_kyc_records']);
        Route::post('/agent/consumer/selfie', [App\Http\Controllers\AgentController::class, 'agent_kyc_selfie']);
        Route::post('/send',[App\Http\Controllers\ConsumerTransactionController::class,'send_money_mobile']);
        Route::post('/deposit',[App\Http\Controllers\ConsumerTransactionController::class,'deposit_money_mobile']);
        Route::get('/balance',[App\Http\Controllers\ConsumerBalanceController::class,'fetch_balance']);
        Route::post('/receiver',[App\Http\Controllers\ConsumerController::class,'get_account_details']);
        Route::get('/transactions', [App\Http\Controllers\ConsumerTransactionController::class,'transaction_history']);
        Route::post('/otp',[App\Http\Controllers\ConsumerController::class,'verify_otp']);
        Route::post('/qr_code',[App\Http\Controllers\ConsumerTransactionController::class,'get_user_by_qr_code']);
        Route::post('/send/business',[App\Http\Controllers\ConsumerTransactionController::class,'send_money_to_business']);
        Route::post('/zesco/token',[App\Http\Controllers\ZescoPurchaseController::class,'zesco_token']);
        Route::post('/zesco/get_client',[App\Http\Controllers\ZescoPurchaseController::class,'process_konse_konse_get_zesco_client_details']);
        Route::post('/talktime/direct',[App\Http\Controllers\DirectTalktimeController::class,'handle_client_talktime_direct_topup']);
        Route::get('/utilities/{id}', [App\Http\Controllers\UtilityBillController::class,'utilitiy_bills']);
        Route::get('/get/bal',[App\Http\Controllers\KonikBalanceController::class,'get_current_balance']);
        Route::post('/dstv/get_client',[App\Http\Controllers\DstvPurchaseController::class,'process_konse_konse_get_dstv_client_details']);
        Route::post('/tv/topup',[App\Http\Controllers\DstvPurchaseController::class,'dstv_topup']);
        Route::get('/dstv/packages',[App\Http\Controllers\DstvPackageController::class,'get_packages']);
        Route::get('/gotv/packages',[App\Http\Controllers\GotvPackageController::class,'get_packages']);
        Route::get('/liquid/packages',[App\Http\Controllers\LiquidTelecomPackageController::class,'get_packages']);
        Route::get('/showmax/packages',[App\Http\Controllers\ShowMaxPackageController::class,'get_packages']);
        Route::get('/topstar/packages',[App\Http\Controllers\TopstarPackageController::class,'get_packages']);
        Route::post('/transaction/charge', [App\Http\Controllers\ConsumerTransactionController::class,'transaction_charge']);
    });

});

Route::prefix('v3/pos')->group(function(){
    Route::post('/login',[App\Http\Controllers\ClientController::class,'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/pay',[App\Http\Controllers\PaymentController::class,'instant_payment_pos']);
        Route::post('/pay/qr_code',[App\Http\Controllers\PaymentController::class,'qr_code_pay_pos']);
        Route::post('/confirm/mno',[App\Http\Controllers\PaymentController::class,'instant_payment_confirmation_manual_pos']);
    });
});
