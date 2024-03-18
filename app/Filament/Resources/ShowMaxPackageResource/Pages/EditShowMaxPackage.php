<?php

namespace App\Filament\Resources\ShowMaxPackageResource\Pages;

use App\Filament\Resources\ShowMaxPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteShowMaxPackagesPermission;

class EditShowMaxPackage extends EditRecord
{
    protected static string $resource = ShowMaxPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
