<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateUsersPermission;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateUsersPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Users",
            "activity" => "Viewed Create Users Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
