<?php

namespace App\Filament\Resources\BusinessTypeResource\Pages;

use App\Filament\Resources\BusinessTypeResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateBusinessesPermission;
use function App\Filament\Resources\checkCreateBusinessTypesPermission;

class CreateBusinessType extends CreateRecord
{
    protected static string $resource = BusinessTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateBusinessTypesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Business Types",
            "activity" => "Viewed Create Business Types Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
