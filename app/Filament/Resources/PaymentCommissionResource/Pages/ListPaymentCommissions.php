<?php

namespace App\Filament\Resources\PaymentCommissionResource\Pages;

use App\Filament\Resources\PaymentCommissionResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreatePaymentCommissionsPermission;
use function App\Filament\Resources\checkReadPaymentCommissionsPermission;

class ListPaymentCommissions extends ListRecords
{
    protected static string $resource = PaymentCommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
               return checkCreatePaymentCommissionsPermission();  
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadPaymentCommissionsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payment Commissions",
            "activity" => "Viewed List Payment Commissions Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
