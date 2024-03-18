<?php

namespace App\Filament\Resources\GoTvPackageResource\Pages;

use App\Filament\Resources\GoTvPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateGoTVPackagesPermission;
use function App\Filament\Resources\checkReadGoTVPackagesPermission;

class ListGoTvPackages extends ListRecords
{
    protected static string $resource = GoTvPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateGoTVPackagesPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadGoTVPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "GoTV Packages",
            "activity" => "Viewed List GoTV Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
