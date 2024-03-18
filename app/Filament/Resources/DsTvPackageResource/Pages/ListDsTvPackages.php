<?php

namespace App\Filament\Resources\DsTvPackageResource\Pages;

use App\Filament\Resources\DsTvPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateDSTVPackagesPermission;
use function App\Filament\Resources\checkReadDSTVPackagesPermission;

class ListDsTvPackages extends ListRecords
{
    protected static string $resource = DsTvPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateDSTVPackagesPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadDSTVPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "DSTV Packages",
            "activity" => "Viewed List DSTV Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
