<?php

namespace App\Filament\Resources\DistrictResource\Pages;

use App\Filament\Resources\DistrictResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateDistrictsPermission;

class CreateDistrict extends CreateRecord
{
    protected static string $resource = DistrictResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateDistrictsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Districts",
            "activity" => "Viewed Create Districts Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
