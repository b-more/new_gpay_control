<?php

namespace App\Filament\Resources\AuditTrailResource\Pages;

use App\Filament\Resources\AuditTrailResource;

use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateAuditTrailsPermission;
use function App\Filament\Resources\checkReadAuditTrailsPermission;


class ListAuditTrails extends ListRecords
{
    protected static string $resource = AuditTrailResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->visible(function(){
    //             return checkCreateAuditTrailsPermission();
    //         }),
    //     ];
    // }


    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadAuditTrailsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Audit Trails",
            "activity" => "Viewed List Audit Trails Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }

}
