<?php

namespace App\Filament\Resources\DsTvPackageResource\Pages;

use App\Filament\Resources\DsTvPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteDSTVPackagesPermission;

class EditDsTvPackage extends EditRecord
{
    protected static string $resource = DsTvPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
