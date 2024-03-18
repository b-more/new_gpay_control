<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Filament\Resources\ProvinceResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateProvincesPermission;

class ListProvinces extends ListRecords
{
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateProvincesPermission();
            }),
        ];
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
