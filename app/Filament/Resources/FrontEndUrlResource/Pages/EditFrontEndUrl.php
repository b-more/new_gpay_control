<?php

namespace App\Filament\Resources\FrontEndUrlResource\Pages;

use App\Filament\Resources\FrontEndUrlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteFrontendURLsPermission;

class EditFrontEndUrl extends EditRecord
{
    protected static string $resource = FrontEndUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
