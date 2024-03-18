<?php

namespace App\Filament\Resources\BusinessCategoryResource\Pages;

use App\Filament\Resources\BusinessCategoryResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateBusinessCategoryPermission;

class CreateBusinessCategory extends CreateRecord
{
    protected static string $resource = BusinessCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateBusinessCategoryPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Business Categories",
            "activity" => "Viewed Create Business Categories Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
