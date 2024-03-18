<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('businesses')->insert([
            [
                'user_id' => 1,
                'account_number' => 'GP343023248',
                'business_type_id' => 2,
                'business_name' => 'Kanongayebo',
                'business_email' => 'imalemelo@gmail.com',
                'business_category_id' => 1
            ]
        ]);
    }
}
