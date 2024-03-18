<?php

namespace App\Filament\Resources\PayoutResource\Pages;

use App\Filament\Resources\PayoutResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreatePayoutsPermission;

class CreatePayout extends CreateRecord
{
    protected static string $resource = PayoutResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreatePayoutsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payouts",
            "activity" => "Viewed Create Payouts Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
