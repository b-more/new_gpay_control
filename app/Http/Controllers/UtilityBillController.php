<?php

namespace App\Http\Controllers;

use App\Models\UtilityBill;
use Illuminate\Http\Request;

class UtilityBillController extends Controller
{
    //fetch utility bill by id
    public function utilitiy_bills($id)
    {
        $utility_bill_list = UtilityBill::where('utility_bill_category_id', $id)->where('is_active', 1)->get();



        $custom_response = [
            "success" => true,
            "message" => "Utility Bill fetched successfully",
            "data" => UtilitiesResource::collection($utility_bill_list)
        ];

        return response()->json($custom_response, 200);
    }
}
