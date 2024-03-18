<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BusinessCategory::all();

        $custom_response = [
            "success" => true,
            "data" => $categories
        ];
        return response()->json($custom_response);
    }

    public function api_list()
    {
        $selected_business = Business::where('is_active',1)->get();
        return response()->json($selected_business);
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
    public function show(BusinessCategory $businessCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessCategory $businessCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessCategory $businessCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessCategory $businessCategory)
    {
        //
    }
}
