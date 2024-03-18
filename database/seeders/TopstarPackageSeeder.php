<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopstarPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("topstar_packages")->insert([
            [
                'name' => 'TopStar DTT Nova',
                'voucher_type' => 'DTT_Nova',
                'voucher_value' => '2500',
                'voucher_id' => 'EX2DVOUT5',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTT Nova',
                'voucher_type' => 'DTT_Nova',
                'voucher_value' => '6000',
                'voucher_id' => 'EWNHCYE57',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTT Basic',
                'voucher_type' => 'DTT_Basic',
                'voucher_value' => '11000',
                'voucher_id' => 'EWNHCYE65',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTT Classic',
                'voucher_type' => 'DTT_Classic',
                'voucher_value' => '5500',
                'voucher_id' => 'EX2DVOUX9',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTT Classic',
                'voucher_type' => 'DTT_Classic',
                'voucher_value' => '16000',
                'voucher_id' => 'EWNHCYFA4',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTH Nova',
                'voucher_type' => 'DTH_Nova',
                'voucher_value' => '2500',
                'voucher_id' => 'EX2DVOUZ7',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTH Nova',
                'voucher_type' => 'DTH_Nova',
                'voucher_value' => '7500',
                'voucher_id' => 'EWNYMZYR1',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTH Smart',
                'voucher_type' => 'DTH_Smart',
                'voucher_value' => '5500',
                'voucher_id' => 'EX2DVOU28',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTH Smart',
                'voucher_type' => 'DTH_Smart',
                'voucher_value' => '16000',
                'voucher_id' => 'EWNYMZYV1',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTH Super',
                'voucher_type' => 'DTH_Super',
                'voucher_value' => '8000',
                'voucher_id' => 'EX2DVOU49',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar DTH Super',
                'voucher_type' => 'DTH_Super',
                'voucher_value' => '23000',
                'voucher_id' => 'EWNYMZYW4',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'TopStar Any Amount',
                'voucher_type' => 'Topstar-Topup',
                'voucher_value' => '23000',
                'voucher_id' => 'EWNHCYE11',
                'is_fixed' => 0,
                'is_active' => 0
            ],
        ]);
    }
}
