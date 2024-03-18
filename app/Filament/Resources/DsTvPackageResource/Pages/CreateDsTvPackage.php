<?php

namespace App\Filament\Resources\DsTvPackageResource\Pages;

use App\Filament\Resources\DsTvPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateDSTVPackagesPermission;

class CreateDsTvPackage extends CreateRecord
{
    protected static string $resource = DsTvPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateDSTVPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "DSTV Packages",
            "activity" => "Viewed Create DSTV Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
