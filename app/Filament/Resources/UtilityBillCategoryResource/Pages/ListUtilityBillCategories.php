<?php

namespace App\Filament\Resources\UtilityBillCategoryResource\Pages;

use App\Filament\Resources\UtilityBillCategoryResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateUtilityBillCategoryPermission;
use function App\Filament\Resources\checkReadUtilityBillCategoryPermission;

class ListUtilityBillCategories extends ListRecords
{
    protected static string $resource = UtilityBillCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateUtilityBillCategoryPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadUtilityBillCategoryPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Utility Bill Categories",
            "activity" => "Viewed List Utility Bill Categories Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
