<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsumerBalance;
use Illuminate\Support\Facades\Auth;

class ConsumerBalanceController extends Controller
{
    public function fetch_balance(){
        $balance = ConsumerBalance::where('consumer_id',Auth::user()->id)->first()->balance;
        $custom_response = [
            "success"=>true,
            "message"=>"Balance fetched successfully",
            "balance"=> number_format($balance,2)
        ];

        return response()->json($custom_response,200);
    }
}
