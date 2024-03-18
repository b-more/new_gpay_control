<?php

namespace App\Filament\Resources\PayoutTransactionResource\Pages;

use App\Filament\Resources\PayoutTransactionResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreatePayoutTransactionsPermission;

class CreatePayoutTransaction extends CreateRecord
{
    protected static string $resource = PayoutTransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreatePayoutTransactionsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Payout Transactions",
            "activity" => "Viewed Create Payout Transactions Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
