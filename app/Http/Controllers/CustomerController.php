<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    public function api_business_customers(Request $request)
    {
        if(Customer::where('business_id', $request->input('nob'))->where('is_deleted',0)->count() > 0){
            $payments = Customer::where('business_id', $request->input('nob'))->where('is_deleted', 0);

            if ($request->has('search') && $request->input('search')) {
                $search = $request->input('search');
                $payments = Customer::where('business_id', $request->input('nob'))->where('is_deleted', 0)->where('id', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%")
                    ->orWhere('updated_at', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            }

            $pageSize = $request->input('pageSize', 10);

            return $payments->orderBy('updated_at','DESC')->paginate($pageSize);
        }else{
            $custom_response = [
                "success" => false,
                "message" => "You have no customers yet"
            ];

            return response()->json($custom_response, 400);
        }
    }

    public function api_business_customer(Request $request)
    {
        if(Customer::where('id', $request->input('customer_id'))->where('is_deleted',0)->count() > 0){
            $payments = Payment::where('business_id', $request->input('nob'))->where('customer_id', $request->input('customer_id'))->where('is_deleted', 0);

            if ($request->has('search') && $request->input('search')) {
                $search = $request->input('search');
                $payments = Payment::where('business_id', $request->input('nob'))->where('customer_id',$request->input('customer_id'))->where('is_deleted', 0)->where('id', 'like', "%$search%")
                    ->orWhere('payment_reference_number', 'like', "%$search%")
                    ->orWhere('payment_channel', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%")
                    ->orWhere('received_amount', 'like', "%$search%")
                    ->orWhere('created_at', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
            }

            $pageSize = $request->input('pageSize', 10);

            $customer_details = Customer::where('id', $request->input('customer_id'))->first();
            $payments_count = Payment::where('business_id', $request->input('nob'))->where('customer_id', $request->input('customer_id'))->where('is_deleted', 0)->count();
            $amount_spent = Payment::where('business_id', $request->input('nob'))->where('customer_id', $request->input('customer_id'))->where('is_deleted', 0)->where('status', 2)->sum('received_amount');
            $transactions_done = $payments->orderBy('updated_at','DESC')->paginate($pageSize);

            $custom_response = [
                "success" => true,
                "message" => "customer details fetched successfully",
                "customer_name" => $customer_details->name,
                "phone_number" => $customer_details->phone_number,
                "email" => $customer_details->email,
                "created_at" => $customer_details->created_at,
                "payments_count" => $payments_count,
                "amount_spent" => $amount_spent,
                "transactions" => $transactions_done
            ];

            return response()->json($custom_response,200);

        }else{
            $custom_response = [
                "success" => false,
                "message" => "You have no customers yet"
            ];

            return response()->json($custom_response, 400);
        }
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
