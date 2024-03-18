<?php

namespace App\Filament\Resources\BusinessResource\Pages;

use App\Filament\Resources\BusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use function App\Filament\Resources\checkDeleteBusinessesPermission;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

     protected function getHeaderActions(): array
     {
         return [

         ];
     }
}
