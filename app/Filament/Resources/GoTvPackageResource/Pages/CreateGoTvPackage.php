<?php

namespace App\Filament\Resources\GoTvPackageResource\Pages;

use App\Filament\Resources\GoTvPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateGoTVPackagesPermission;

class CreateGoTvPackage extends CreateRecord
{
    protected static string $resource = GoTvPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateGoTVPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "GoTV Packages",
            "activity" => "Viewed Create GoTV Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
