<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_commissions')->insert([
            [
                'name' => 'Collections',
                'category' => 'collections',
                'description' => 'collections 2.50% to cGrate & 2% to GeePay',
                'cgrate_percentage' => '2.5',
                'geepay_percentage' => '2',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Disbursements',
                'category' => 'disbursement',
                'description' => 'disbursements K5 per transaction to cGrate & 2.5% to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '2.5',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Wallet to Bank',
                'category' => 'wallet to bank',
                'description' => 'Wallet to bank K5 per transction to cGrate & 1.5% to GeePay',
                'cgrate_percentage' => '2.5',
                'geepay_percentage' => '2',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Wallet to wallet',
                'category' => 'wallet to wallet',
                'description' => 'Wallet to wallet null to cGrate & nulll to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Wallet to Mobile',
                'category' => 'wallet to mobile',
                'description' => 'Wallet to mobile null to cGrate & K10 to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile to Wallet',
                'category' => 'mobile to mobile',
                'description' => 'Mobile to wallet no charge',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Commission earned per transaction',
                'category' => 'Commission transaction',
                'description' => 'Wallet to mobile 50% to cGrate & 50% to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DSTV',
                'category' => 'DSTV',
                'description' => 'DSTV bill commission 1.29% to cGrate & 0.64% to GeePay',
                'cgrate_percentage' => '1.29',
                'geepay_percentage' => '0.64',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'GoTV',
                'category' => 'GoTv',
                'description' => 'GoTv bill commission 1.29% to cGrate & 0.64% to GeePay',
                'cgrate_percentage' => '1.29',
                'geepay_percentage' => '0.64',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Muvi',
                'category' => 'Muvi',
                'description' => 'GoTv bill commission null to cGrate & null to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Airtel Airtime',
                'category' => 'Airtel Airtime',
                'description' => 'Airtel Airtime commission 3.23% to cGrate & 1.62% to GeePay',
                'cgrate_percentage' => '3.23',
                'geepay_percentage' => '1.62',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'MTN Airtime',
                'category' => 'MTN Airtime',
                'description' => 'MTN Airtime commission 3.23% to cGrate & 1.62% to GeePay',
                'cgrate_percentage' => '3.23',
                'geepay_percentage' => '1.62',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ZAMTEL Airtime',
                'category' => 'ZAMTEL Airtime',
                'description' => 'ZAMTEL Airtime commission 4.31% to cGrate & 2.16% to GeePay',
                'cgrate_percentage' => '4.31',
                'geepay_percentage' => '2.16',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ZUKU',
                'category' => 'ZUKU',
                'description' => 'ZUKU bill commission null to cGrate & null to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ZESCO',
                'category' => 'ZESCO',
                'description' => 'ZESCO commission 1.29% to cGrate & 0.64% to GeePay',
                'cgrate_percentage' => '1.29',
                'geepay_percentage' => '0.64',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Water Utility',
                'category' => 'Water Utility',
                'description' => 'Water Utility bill commission null to cGrate & null to GeePay',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '0',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Liquid Data',
                'category' => 'Liquid Data',
                'description' => 'Liquid Data commission 5.17% to cGrate & 2.58% to GeePay',
                'cgrate_percentage' => '1.29',
                'geepay_percentage' => '0.64',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Payout',
                'category' => 'Payout',
                'description' => 'Payout to business account via Bank',
                'cgrate_percentage' => '0',
                'geepay_percentage' => '5.2',
                'cgrate_fixed_charge' => '0',
                'geepay_fixed_charge' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
