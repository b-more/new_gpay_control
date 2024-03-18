<?php

namespace App\Filament\Resources\LiquidTelecomPackageResource\Pages;

use App\Filament\Resources\LiquidTelecomPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateLiquidTelecomPackagesPermission;

class CreateLiquidTelecomPackage extends CreateRecord
{
    protected static string $resource = LiquidTelecomPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateLiquidTelecomPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Liquid Telecom Packages",
            "activity" => "Viewed Create Liquid Telecom Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
