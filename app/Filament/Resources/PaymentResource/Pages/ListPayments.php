<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreatePaymentsPermission;
use function App\Filament\Resources\checkReadPaymentsPermission;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->visible(function(){
    //             return checkCreatePaymentsPermission();
    //         }),
    //     ];
    // }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadPaymentsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payments",
            "activity" => "Viewed List Payments Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
