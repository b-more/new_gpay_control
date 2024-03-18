<?php

namespace App\Filament\Resources\TopstarPackageResource\Pages;

use App\Filament\Resources\TopstarPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateTopStarPackagesPermission;
use function App\Filament\Resources\checkReadTopStarPackagesPermission;

class ListTopstarPackages extends ListRecords
{
    protected static string $resource = TopstarPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateTopStarPackagesPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadTopStarPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "TopStar Packages",
            "activity" => "Viewed List TopStar Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
