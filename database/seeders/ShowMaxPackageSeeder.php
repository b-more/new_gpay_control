<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowMaxPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("show_max_packages")->insert([
            [
                'name' => 'DStv ShowMax',
                'voucher_type' => 'ShowMax',
                'voucher_value' => '99500',
                'voucher_id' => 'FDF4GGZO7',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'DStv ShowMax',
                'voucher_type' => 'ShowMax',
                'voucher_value' => '121000',
                'voucher_id' => 'FDFSAETG2',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'DStv ShowMax',
                'voucher_type' => 'ShowMax',
                'voucher_value' => '129000',
                'voucher_id' => 'FDF4EGSO6',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'DStv ShowMax',
                'voucher_type' => 'ShowMax',
                'voucher_value' => '67250',
                'voucher_id' => 'FDF4ZRN55',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'DStv ShowMax',
                'voucher_type' => 'ShowMax',
                'voucher_value' => '33250',
                'voucher_id' => 'FDF435JJ2',
                'is_fixed' => 1,
                'is_active' => 1
            ],
        ]);
    }
}
