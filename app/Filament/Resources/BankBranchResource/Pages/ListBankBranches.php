<?php

namespace App\Filament\Resources\BankBranchResource\Pages;

use App\Filament\Resources\BankBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use function App\Filament\Resources\checkCreateBankBranchesPermission;

class ListBankBranches extends ListRecords
{
    protected static string $resource = BankBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateBankBranchesPermission();
            }),
        ];
    }
}
