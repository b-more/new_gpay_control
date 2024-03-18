<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('business_types')->insert([
            [
                "name" => "Sole Trader/ Freelancer"
            ],
            [
                "name" => "Private Limited / Public Company"
            ],
            [
                "name" => "N.G.O / Society"
            ]
        ]);
    }
}
