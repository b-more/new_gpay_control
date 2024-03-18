<?php

namespace App\Http\Controllers;

use App\Models\ShowMaxPackage;
use Illuminate\Http\Request;

class ShowMaxPackageController extends Controller
{
    public function get_packages()
    {
        $packages = ShowMaxPackage::where("is_active", 1)->get();

        $custom_response = [
            "success" => true,
            "message" => "data fetched successfully",
            "data" => $packages
        ];

        return response()->json($custom_response, 200);
    }
}
