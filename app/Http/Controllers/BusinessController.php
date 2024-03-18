<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\CurrentBalance;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{

    public function api_business_dashboard(Request $request)
    {
        /*header('Access-Control-Allow-Origin: https://ontechcloud.tech');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");*/

        $current_payments_balance = CurrentBalance::where('business_id', Auth::user()->business_id)->first()->payments;
        $current_disbursement_balance = CurrentBalance::where('business_id', Auth::user()->business_id)->first()->disbursement;
        $refunds_count = Payment::where('business_id', Auth::user()->business_id)->count();
        $refunds_total = Payment::where('business_id', Auth::user()->business_id)->where('is_refunded', 1)->sum('received_amount');
        $payments_total_amount = Payment::where('business_id', Auth::user()->business_id)->where('status', 2)->where('is_refunded', 0)->sum('received_amount');
        $payments_total = Payment::where('business_id', Auth::user()->business_id)->where('is_refunded', 0)->count();


        $custom_response = [
            "success" => true,
            "message" => "data fetched successfully",
            "current_payments_balance"=>$current_payments_balance,
            "current_disbursement_balance"=>$current_disbursement_balance,
            "refunds_count"=>$refunds_count,
            "refunds_total"=>$refunds_total,
            "payments_total"=>$payments_total,
            "payments_total_amount"=>$payments_total_amount,
        ];

        return response()->json($custom_response, 200);
    }

    public function api_dashboard_recent_payments(Request $request){
        $recent_payments = Payment::where('business_id', Auth::user()->business_id)->where('status', 2)->where('is_refunded', 0)->latest()->take(5)->get();

        return $recent_payments;
    }
    /**
     * Display a listing of the resource.
     */
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
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        //
    }
}
