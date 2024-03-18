<?php

namespace App\Filament\Resources\BusinessCategoryResource\Pages;

use App\Filament\Resources\BusinessCategoryResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateBusinessCategoryPermission;
use function App\Filament\Resources\checkReadBusinessCategoryPermission;

class ListBusinessCategories extends ListRecords
{
    protected static string $resource = BusinessCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateBusinessCategoryPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadBusinessCategoryPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Business Categories",
            "activity" => "Viewed List Business Categories Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
