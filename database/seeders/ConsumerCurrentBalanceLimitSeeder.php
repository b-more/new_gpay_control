<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumerCurrentBalanceLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("consumer_current_balance_limits")->insert([
            [
                "user_id" => 1,
                "name" => "Default",
                "amount" => "25000",
                "is_active" => 1,
                "is_deleted" => 0,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
