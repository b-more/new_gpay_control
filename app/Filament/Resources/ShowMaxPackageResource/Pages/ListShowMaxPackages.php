<?php

namespace App\Filament\Resources\ShowMaxPackageResource\Pages;

use App\Filament\Resources\ShowMaxPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateShowMaxPackagesPermission;
use function App\Filament\Resources\checkReadShowMaxPackagesPermission;

class ListShowMaxPackages extends ListRecords
{
    protected static string $resource = ShowMaxPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateShowMaxPackagesPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadShowMaxPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "ShowMax Packages",
            "activity" => "Viewed List ShowMax Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
