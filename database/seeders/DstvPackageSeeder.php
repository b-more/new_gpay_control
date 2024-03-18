<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DstvPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dstv_packages')->insert([
            [
                'name' => 'Access',
                'voucher_type' => 'Access',
                'voucher_value' => '18000',
                'voucher_id' => 'EKW5OR5O9',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Family',
                'voucher_type' => 'Family',
                'voucher_value' => '32500',
                'voucher_id' => 'EKW5OR5Q3',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Compact',
                'voucher_type' => 'Compact',
                'voucher_value' => '50000',
                'voucher_id' => 'EKW5OR5R5',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Compact Plus',
                'voucher_type' => 'CompactPLUS',
                'voucher_value' => '75000',
                'voucher_id' => 'EKW5OR5T4',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Premium',
                'voucher_type' => 'Premium',
                'voucher_value' => '120000',
                'voucher_id' => 'EKW5OR5V1',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'GOtv Any Amount',
                'voucher_type' => 'DStv-Topup',
                'voucher_value' => '30000',
                'voucher_id' => 'ELOA1SA26',
                'is_fixed' => 0,
                'is_active' => 0
            ]
        ]);
    }
}
