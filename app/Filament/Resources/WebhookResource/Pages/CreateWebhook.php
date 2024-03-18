<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateWebhookPermission;

class CreateWebhook extends CreateRecord
{
    protected static string $resource = WebhookResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateWebhookPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Webhooks",
            "activity" => "Viewed Create Webhooks Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
