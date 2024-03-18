<?php

namespace App\Filament\Resources\LiquidTelecomPackageResource\Pages;

use App\Filament\Resources\LiquidTelecomPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateLiquidTelecomPackagesPermission;
use function App\Filament\Resources\checkReadLiquidTelecomPackagesPermission;

class ListLiquidTelecomPackages extends ListRecords
{
    protected static string $resource = LiquidTelecomPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateLiquidTelecomPackagesPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadLiquidTelecomPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Liquid Telecom Packages",
            "activity" => "Viewed List Liquid Telecom Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
