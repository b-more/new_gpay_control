<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiquidTelecomPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("liquid_telecom_packages")->insert([
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '4500',
                'voucher_id' => 'EZPB41RQ5',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '7000',
                'voucher_id' => 'EZPB41RV7',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '11000',
                'voucher_id' => 'EZPB41RX0',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '16000',
                'voucher_id' => 'EZPB41RZ3',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED-Promotion',
                'voucher_value' => '22000',
                'voucher_id' => 'E4CN1AXE0',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED-Gaming',
                'voucher_value' => '40000',
                'voucher_id' => 'FDBKOU1F7',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '68000',
                'voucher_id' => 'EZPB41R24',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '99500',
                'voucher_id' => 'EZPB41R35',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '149500',
                'voucher_id' => 'EZPB41R48',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Liquid LiTESPEED',
                'voucher_type' => 'LiTESPEED',
                'voucher_value' => '229500',
                'voucher_id' => 'EZPB41R59',
                'is_fixed' => 1,
                'is_active' => 1
            ],
        ]);
    }
}
