<?php

namespace App\Filament\Resources\PayoutTransactionResource\Pages;

use App\Filament\Resources\PayoutTransactionResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreatePayoutTransactionsPermission;
use function App\Filament\Resources\checkReadPayoutTransactionsPermission;

class ListPayoutTransactions extends ListRecords
{
    protected static string $resource = PayoutTransactionResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->visible(function(){
    //             return checkCreatePayoutTransactionsPermission();
    //         }),
    //     ];
    // }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadPayoutTransactionsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payout Transactions",
            "activity" => "Viewed List Payout Transactions Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
