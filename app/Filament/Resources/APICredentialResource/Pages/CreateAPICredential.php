<?php

namespace App\Filament\Resources\APICredentialResource\Pages;

use App\Filament\Resources\APICredentialResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateAPICredentialPermission;
use function App\Filament\Resources\checkReadAPICredentialPermission;

class CreateAPICredential extends CreateRecord
{
    protected static string $resource = APICredentialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateAPICredentialPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "API Credentials",
            "activity" => "Viewed Create API Credentials Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
