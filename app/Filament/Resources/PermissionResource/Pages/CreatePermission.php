<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreatePermissionsPermission;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreatePermissionsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Permissions",
            "activity" => "Viewed Create Permissions Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }


}
