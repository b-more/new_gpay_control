<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bank_names')->insert([
            [
              'name' => 'Bank of Zambia',
              'clearing_center' => '00'
            ],
            [
              'name' => 'Zambia National Commercial Bank',
              'clearing_center' => '01'
            ],
            [
              'name' => 'Absa Bank Zambia PLC',
              'clearing_center' => '02'
            ],
            [
              'name' => 'Citibank Zambia',
              'clearing_center' => '03'
            ],
            [
              'name' => 'Stanbic Bank Zambia',
              'clearing_center' => '04'
            ],
            [
              'name' => 'Standard Chartered Bank',
              'clearing_center' => '06'
            ],
            [
              'name' => 'Indo Zambia Bank',
              'clearing_center' => '09'
            ],
            [
              'name' => 'Zambia Industrial Commercial Bank',
              'clearing_center' => '14'
            ],
            [
              'name' => 'Intermarket Banking Corporation',
              'clearing_center' => '15'
            ],
            [
              'name' => 'Investrust Bank',
              'clearing_center' => '17'
            ],
            [
              'name' => 'The United Bank of Zambia',
              'clearing_center' => '18'
            ],
            [
              'name' => 'Bank of China',
              'clearing_center' => '19'
            ],
            [
              'name' => 'BancABC',
              'clearing_center' => '20'
            ],
            [
              'name' => 'AB Bank',
              'clearing_center' => '21'
            ],
            [
              'name' => 'First National Bank',
              'clearing_center' => '26'
            ],
            [
              'name' => 'First Capital Bank',
              'clearing_center' => '28'
            ],
            [
              'name' => 'First Alliance Bank',
              'clearing_center' => '34'
            ],
            [
              'name' => 'Access Bank',
              'clearing_center' => '35'
            ],
            [
              'name' => 'Ecobank',
              'clearing_center' => '36'
            ],
            [
              'name' => 'United Bank for Africa',
              'clearing_center' => '37'
            ],
            [
              'name' => 'Zambia National Building Society',
              'clearing_center' => '51'
            ],
            [
              'name' => 'Bayport Financial Services',
              'clearing_center' => '55'
            ],
            [
              'name' => 'National Savings and Credit Bank',
              'clearing_center' => '58'
            ]  
          ]);
          
    }
}
