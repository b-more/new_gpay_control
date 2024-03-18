<?php

namespace App\Filament\Resources\PaymentCommissionResource\Pages;

use App\Filament\Resources\PaymentCommissionResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreatePaymentCommissionsPermission;

class CreatePaymentCommission extends CreateRecord
{
    protected static string $resource = PaymentCommissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreatePaymentCommissionsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payment Commissions",
            "activity" => "Viewed Create Payment Commissions Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
