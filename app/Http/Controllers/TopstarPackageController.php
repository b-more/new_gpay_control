<?php

namespace App\Http\Controllers;

use App\Models\TopstarPackage;
use Illuminate\Http\Request;

class TopstarPackageController extends Controller
{
    public function get_packages()
    {
        $packages = TopstarPackage::where("is_active", 1)->get();

        $custom_response = [
            "success" => true,
            "message" => "data fetched successfully",
            "data" => $packages
        ];

        return response()->json($custom_response, 200);
    }
}
