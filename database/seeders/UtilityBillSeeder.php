<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilityBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("utility_bills")->insert([
            [
                "utility_bill_category_id" => 1, //Airtime
                "name" => "Airtel Direct TopUp",
                "image" => "airtel.png"
            ],
            [
                "utility_bill_category_id" => 1, //Airtime
                "name" => "MTN Direct TopUp",
                "image" => "mtn.png"
            ],
            [
                "utility_bill_category_id" => 1, //Airtime
                "name" => "Zamtel Direct TopUp",
                "image" => "zamtel.png"
            ],
            [
                "utility_bill_category_id" => 5, //Ellectricity
                "name" => "ZESCO Prepaid",
                "image" => "zesco.png"
            ]
        ]);
    }
}
