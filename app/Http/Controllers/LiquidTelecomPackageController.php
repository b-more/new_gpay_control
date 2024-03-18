<?php

namespace App\Http\Controllers;

use App\Models\LiquidTelecomPackage;
use Illuminate\Http\Request;

class LiquidTelecomPackageController extends Controller
{
    public function get_packages()
    {
        $packages = LiquidTelecomPackage::where("is_active", 1)->get();

        $custom_response = [
            "success" => true,
            "message" => "data fetched successfully",
            "data" => $packages
        ];

        return response()->json($custom_response, 200);
    }
}
