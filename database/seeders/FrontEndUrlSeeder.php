<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrontEndUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('front_end_urls')->insert([
            [
                "domain" => "https://geepaybiz.ontechcloud.tech"
            ]
        ]);
    }
}
