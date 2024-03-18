<?php

namespace App\Filament\Resources\FrontEndUrlResource\Pages;

use App\Filament\Resources\FrontEndUrlResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateFrontendURLsPermission;
use function App\Filament\Resources\checkReadFrontendURLsPermission;

class ListFrontEndUrls extends ListRecords
{
    protected static string $resource = FrontEndUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                return checkCreateFrontendURLsPermission();
            }),
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadFrontendURLsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Frontend URLs",
            "activity" => "Viewed List Frontend URLs Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
