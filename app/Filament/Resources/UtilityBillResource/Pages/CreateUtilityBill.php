<?php

namespace App\Filament\Resources\UtilityBillResource\Pages;

use App\Filament\Resources\UtilityBillResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateUtilityBillPermission;

class CreateUtilityBill extends CreateRecord
{
    protected static string $resource = UtilityBillResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateUtilityBillPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Utility Bills",
            "activity" => "Viewed Create Utility Bills Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
