<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorisationCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("authorisation_comments")->insert([
            [
                "user_id" => 1,
                "comment" => "Cheque bounced",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "user_id" => 1,
                "comment" => "Incorrect details",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
