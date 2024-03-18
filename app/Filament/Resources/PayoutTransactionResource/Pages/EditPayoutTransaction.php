<?php

namespace App\Filament\Resources\PayoutTransactionResource\Pages;

use App\Filament\Resources\PayoutTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeletePayoutTransactionsPermission;

class EditPayoutTransaction extends EditRecord
{
    protected static string $resource = PayoutTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
