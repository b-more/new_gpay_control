<?php

namespace App\Filament\Resources\APICredentialResource\Pages;

use App\Filament\Resources\APICredentialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteAPICredentialPermission;

class EditAPICredential extends EditRecord
{
    protected static string $resource = APICredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
