<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilityBillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("utility_bill_categories")->insert([
            [
                "name" => "Airtime"
            ],
            [
                "name" => "Internet"
            ],
            [
                "name" => "Airtime"
            ],
            [
                "name" => "Betting"
            ],
            [
                "name" => "Tv"
            ],
            [
                "name" => "Airtime"
            ],
            [
                "name" => "Electricity"
            ],
            [
                "name" => "Water"
            ],
            [
                "name" => "School"
            ],
            [
                "name" => "Loans"
            ]
        ]);
    }
}
