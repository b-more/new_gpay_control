<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BankName;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //$this->call(ConsumerSeeder::class);
        $this->call(BusinessCategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(class:UserSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(BusinessTypeSeeder::class);
        $this->call(PaymentCommissionSeeder::class);
        //$this->call(ConsumerBalanceSeeder::class);
        $this->call(FrontEndUrlSeeder::class);
        $this->call(UtilityBillCategorySeeder::class);
        $this->call(UtilityBillSeeder::class);
        $this->call(DstvPackageSeeder::class);
        $this->call(GoTvPackageSeeder::class);
        $this->call(LiquidTelecomPackageSeeder::class);
        $this->call(ShowMaxPackageSeeder::class);
        $this->call(TopstarPackageSeeder::class);
        $this->call(BankNameSeeder::class);
        $this->call(BankBranchSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(ConsumerCurrentBalanceLimitSeeder::class);
        $this->call(ConsumerDailyWithdrawLimitSeeder::class);

    }
}
