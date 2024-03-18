<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreatePermissionsPermission;
use function App\Filament\Resources\checkReadPermissionPermission;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    public function mount(): void
    {
        $user = Auth::user();
        //abort_unless(checkReadPermissionPermission(),403);
        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Permissions",
            "activity" => "Viewed List permissions",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }

    /*protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreatePermissionsPermission();
            }),
        ];
    }*/
}
