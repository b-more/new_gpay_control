<?php

namespace App\Filament\Resources\LiquidTelecomPackageResource\Pages;

use App\Filament\Resources\LiquidTelecomPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteLiquidTelecomPackagesPermission;

class EditLiquidTelecomPackage extends EditRecord
{
    protected static string $resource = LiquidTelecomPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
