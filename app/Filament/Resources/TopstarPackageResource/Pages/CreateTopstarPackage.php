<?php

namespace App\Filament\Resources\TopstarPackageResource\Pages;

use App\Filament\Resources\TopstarPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateTopStarPackagesPermission;

class CreateTopstarPackage extends CreateRecord
{
    protected static string $resource = TopstarPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateTopStarPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "TopStar Packages",
            "activity" => "Viewed Create TopStar Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
