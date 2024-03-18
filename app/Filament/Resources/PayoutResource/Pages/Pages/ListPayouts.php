<?php

namespace App\Filament\Resources\PayoutResource\Pages;

use App\Filament\Resources\PayoutResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreatePayoutsPermission;
use function App\Filament\Resources\checkReadPayoutsPermission;

class ListPayouts extends ListRecords
{
    protected static string $resource = PayoutResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->visible(function(){
    //             return checkCreatePayoutsPermission();
    //         }),
    //     ];
    // }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadPayoutsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payouts",
            "activity" => "Viewed List Payouts Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
