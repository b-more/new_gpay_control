<?php

namespace App\Filament\Resources\DistrictResource\Pages;

use App\Filament\Resources\DistrictResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateDistrictsPermission;
use function App\Filament\Resources\checkReadDistrictsPermission;

class ListDistricts extends ListRecords
{
    protected static string $resource = DistrictResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateDistrictsPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadDistrictsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Districts",
            "activity" => "Viewed List Districts Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
