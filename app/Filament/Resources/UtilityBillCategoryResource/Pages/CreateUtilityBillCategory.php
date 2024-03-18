<?php

namespace App\Filament\Resources\UtilityBillCategoryResource\Pages;

use App\Filament\Resources\UtilityBillCategoryResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateUtilityBillCategoryPermission;

class CreateUtilityBillCategory extends CreateRecord
{
    protected static string $resource = UtilityBillCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateUtilityBillCategoryPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Utility Bill Categories",
            "activity" => "Viewed Create Utility Bill Categories Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
