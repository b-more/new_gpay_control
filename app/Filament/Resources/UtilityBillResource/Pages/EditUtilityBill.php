<?php

namespace App\Filament\Resources\UtilityBillResource\Pages;

use App\Filament\Resources\UtilityBillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteUtilityBillPermission;

class EditUtilityBill extends EditRecord
{
    protected static string $resource = UtilityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
