<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateCountriesPermission;
use function App\Filament\Resources\checkReadCountriesPermission;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateCountriesPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadCountriesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Countries",
            "activity" => "Viewed List Countries Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
