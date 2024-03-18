<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumerDailyWithdrawLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("consumer_daily_withdraw_limits")->insert([
            [
                "user_id" => 1,
                "name" => "Default",
                "amount" => "20000",
                "is_active" => 1,
                "is_deleted" => 0,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
