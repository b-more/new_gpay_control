<?php

namespace App\Filament\Resources\UtilityBillResource\Pages;

use App\Filament\Resources\UtilityBillResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateUtilityBillPermission;
use function App\Filament\Resources\checkReadUtilityBillPermission;

class ListUtilityBills extends ListRecords
{
    protected static string $resource = UtilityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateUtilityBillPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadUtilityBillPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Utility Bills",
            "activity" => "Viewed List Utility Bills Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
