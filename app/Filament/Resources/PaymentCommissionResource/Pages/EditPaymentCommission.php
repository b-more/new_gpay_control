<?php

namespace App\Filament\Resources\PaymentCommissionResource\Pages;

use App\Filament\Resources\PaymentCommissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeletePaymentCommissionsPermission;

class EditPaymentCommission extends EditRecord
{
    protected static string $resource = PaymentCommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
