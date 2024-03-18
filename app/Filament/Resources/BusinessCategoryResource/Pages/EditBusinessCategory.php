<?php

namespace App\Filament\Resources\BusinessCategoryResource\Pages;

use App\Filament\Resources\BusinessCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteBusinessCategoryPermission;

class EditBusinessCategory extends EditRecord
{
    protected static string $resource = BusinessCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
