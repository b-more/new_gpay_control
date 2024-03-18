<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agents')->insert([
            [
                "name" => "Bmore Mulenga",
                "email" => "mulengablessmore@gmail.com",
                "phone_number" => "260779205949",
                "image" => "null",
                "nrc_number" => "123456/78/9",
                "password" => Hash::make("New.1234"),
                "district_id" => 1,
                "province_id" => 1,
                "is_active" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
