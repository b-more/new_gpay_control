<?php

namespace App\Filament\Resources\UtilityBillCategoryResource\Pages;

use App\Filament\Resources\UtilityBillCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteUtilityBillCategoryPermission;

class EditUtilityBillCategory extends EditRecord
{
    protected static string $resource = UtilityBillCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
