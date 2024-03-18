<?php

namespace App\Filament\Resources\AuditTrailResource\Pages;

use App\Filament\Resources\AuditTrailResource;

use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkReadAuditTrailsPermission;


class CreateAuditTrail extends CreateRecord
{
    protected static string $resource = AuditTrailResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadAuditTrailsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Audit Trails",
            "activity" => "Viewed Create Audit Trails Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }

}
