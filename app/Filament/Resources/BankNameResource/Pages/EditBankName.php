<?php

namespace App\Filament\Resources\BankNameResource\Pages;

use App\Filament\Resources\BankNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteBankNamesPermission;

class EditBankName extends EditRecord
{
    protected static string $resource = BankNameResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
