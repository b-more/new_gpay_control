<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateCountriesPermission;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateCountriesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Countries",
            "activity" => "Viewed Create Countries Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
