<?php

namespace App\Filament\Resources\GoTvPackageResource\Pages;

use App\Filament\Resources\GoTvPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteGoTVPackagesPermission;

class EditGoTvPackage extends EditRecord
{
    protected static string $resource = GoTvPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
