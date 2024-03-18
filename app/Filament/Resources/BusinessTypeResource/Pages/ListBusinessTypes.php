<?php

namespace App\Filament\Resources\BusinessTypeResource\Pages;

use App\Filament\Resources\BusinessTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use function App\Filament\Resources\checkCreateBusinessTypePermission;
use function App\Filament\Resources\checkReadBusinessTypePermission;

class ListBusinessTypes extends ListRecords
{
    protected static string $resource = BusinessTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
