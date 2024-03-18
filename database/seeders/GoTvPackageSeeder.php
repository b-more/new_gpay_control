<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoTvPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('go_tv_packages')->insert([
            [
                'name' => 'GOtv Lite (monthly)',
                'voucher_type' => 'GOtvLite',
                'voucher_value' => '3500',
                'voucher_id' => 'FG5CJ2UT9',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'GOtv Value',
                'voucher_type' => 'GOtv',
                'voucher_value' => '11000',
                'voucher_id' => 'EKW5O63X4',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'GOtv PLUS',
                'voucher_type' => 'GOtvPLUS',
                'voucher_value' => '18500',
                'voucher_id' => 'EM3GBUMT0',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'GOtv MAX',
                'voucher_type' => 'GOtvMax',
                'voucher_value' => '25000',
                'voucher_id' => 'EXAK2N1Q1',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'GOtv SUPA',
                'voucher_type' => 'GOtvSupa',
                'voucher_value' => '30000',
                'voucher_id' => 'FDSHUMKC9',
                'is_fixed' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'GOtv Any Amount',
                'voucher_type' => 'GOtv-Topup',
                'voucher_value' => '30000',
                'voucher_id' => 'ELOA1XKZ1',
                'is_fixed' => 0,
                'is_active' => 0
            ]
        ]);
    }
}
