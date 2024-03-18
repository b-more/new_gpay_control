<?php

namespace App\Filament\Resources\BankBranchResource\Pages;

use App\Filament\Resources\BankBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteBankBranchesPermission;

class EditBankBranch extends EditRecord
{
    protected static string $resource = BankBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
