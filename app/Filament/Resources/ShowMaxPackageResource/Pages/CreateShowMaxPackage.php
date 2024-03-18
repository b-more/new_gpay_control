<?php

namespace App\Filament\Resources\ShowMaxPackageResource\Pages;

use App\Filament\Resources\ShowMaxPackageResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateShowMaxPackagesPermission;

class CreateShowMaxPackage extends CreateRecord
{
    protected static string $resource = ShowMaxPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateShowMaxPackagesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "ShowMax Packages",
            "activity" => "Viewed Create ShowMax Packages Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
