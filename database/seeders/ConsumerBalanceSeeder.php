<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsumerBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consumer_balances')->insert([
            [
                'consumer_id' => 1,
                'balance' => '10000',
            ],
            [
                'consumer_id' => 2,
                'balance' => '10000',
            ],
            [
                'consumer_id' => 3,
                'balance' => '10000',
            ],
            [
                'consumer_id' => 4,
                'balance' => '10000',
            ]
        ]);
    }
}
