<?php

namespace App\Filament\Resources\BankNameResource\Pages;

use App\Filament\Resources\BankNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use function App\Filament\Resources\checkCreateBankNamesPermission;

class ListBankNames extends ListRecords
{
    protected static string $resource = BankNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateBankNamesPermission();
            }),
        ];
    }
}
