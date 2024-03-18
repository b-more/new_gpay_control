<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Filament\Resources\ProvinceResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateProvincesPermission;

class CreateProvince extends CreateRecord
{
    protected static string $resource = ProvinceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateProvincesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Provinces",
            "activity" => "Viewed Create Provinces Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
