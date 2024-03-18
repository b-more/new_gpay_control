<?php

namespace App\Filament\Resources\FrontEndUrlResource\Pages;

use App\Filament\Resources\FrontEndUrlResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateFrontendURLsPermission;

class CreateFrontEndUrl extends CreateRecord
{
    protected static string $resource = FrontEndUrlResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateFrontendURLsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Frontend URLs",
            "activity" => "Viewed Create Frontend URLs Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
