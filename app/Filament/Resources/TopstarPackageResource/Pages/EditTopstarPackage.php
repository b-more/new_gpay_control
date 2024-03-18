<?php

namespace App\Filament\Resources\TopstarPackageResource\Pages;

use App\Filament\Resources\TopstarPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteTopStarPackagesPermission;

class EditTopstarPackage extends EditRecord
{
    protected static string $resource = TopstarPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
