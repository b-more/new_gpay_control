<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\AuditTrail;
use App\Models\Permission;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;


    public function mount(): void
    {
        $user = Auth::user();
        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Permissions",
            "activity" => "Viewed List permissions",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate()
    {
        $record = $this->record;
        Log::info("created record", ["record" => $record]);

        $modules = [
            [
                "name" => "Accumulative Balances",
            ],
            [
                "name" => "Agents",
            ],
            [
                "name" => "API Credentials",
            ],
            [
                "name" => "Audit Trails",
            ],
            [
                "name" => "Auth Activity Trails",
            ],
            [
                "name" => "Business Category",
            ],
            [
                "name" => "Businesses",
            ],
            [
                "name" => "Business Types",
            ],
            [
                "name" => "Client Audit Trails",
            ],
            [
                "name" => "Clients",
            ],
            [
                "name" => "Commission Received",
            ],
            [
                "name" => "Commissions",
            ],
            [
                "name" => "Consumer Balances",
            ],
            [
                "name" => "Consumer Commissions",
            ],
            [
                "name" => "Consumer Commission Structure",
            ],
            [
                "name" => "Consumer Current Balance Limits",
            ],
            [
                "name" => "Consumer Daily Withdraw Limits",
            ],
            [
                "name" => "Consumers",
            ],
            [
                "name" => "Consumer Transactions",
            ],
            [
                "name" => "Countries",
            ],
            [
                "name" => "Current Balance",
            ],
            [
                "name" => "Customers",
            ],
            [
                "name" => "Bank Branches",
            ],
            [
                "name" => "Bank Names",
            ],
            [
                "name" => "Deposits",
            ],
            [
                "name" => "Deposit Transactions",
            ],
            [
                "name" => "Disputes",
            ],
            [
                "name" => "Districts",
            ],
            [
                "name" => "DSTV Packages",
            ],
            [
                "name" => "Front-end URLs",
            ],
            [
                "name" => "GoTV Packages",
            ],
            [
                "name" => "Liquid Telecom Packages",
            ],
            [
                "name" => "NRC Details",
            ],
            [
                "name" => "Payment Commissions",
            ],
            [
                "name" => "Payment Links",
            ],
            [
                "name" => "Payments",
            ],
            [
                "name" => "Payouts",
            ],
            [
                "name" => "Payout Transactions",
            ],
            [
                "name" => "Permissions",
            ],
            [
                "name" => "Provinces",
            ],
            [
                "name" => "Refunds",
            ],
            [
                "name" => "Reports",
            ],
            [
                "name" => "Report Types",
            ],
            [
                "name" => "Roles",
            ],
            [
                "name" => "Security Red Flags",
            ],
            [
                "name" => "ShowMax Packages",
            ],
            [
                "name" => "Statuses",
            ],
            [
                "name" => "TopStar Packages",
            ],
            [
                "name" => "Transfers",
            ],
            [
                "name" => "Two Factors",
            ],
            [
                "name" => "Users",
            ],
            [
                "name" => "User Types",
            ],
            [
                "name" => "Utility Bill Categories",
            ],
            [
                "name" => "Utility Bills",
            ],
            [
                "name" => "Webhooks",
            ],
            [
                "name" => "Payout Confirm",
            ],
            [
                "name" => "Payout Failed",
            ],
            [
                "name" => "Payout Authorise",
            ],
            [
                "name" => "Payout Cancel",
            ],
            [
                "name" => "Payout Initiate",
            ],
        ];

        foreach ($modules as $module)
        {
            $new_permission = Permission::create([
                "role_id" => $record->id,
                "module" => $module["name"],
                "create" => 0,
                "read" => 1,
                "update" => 0,
                "delete" => 0,
                "initiate" => 0,
                "authorizer" => 0
            ]);

            $new_permission->save();
        }

        $user = Auth::user();
        $record = $this->record;
        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Roles",
            "activity" => "Saved record => ".$record,
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
