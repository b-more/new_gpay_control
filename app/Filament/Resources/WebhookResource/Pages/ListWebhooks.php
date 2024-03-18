<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateWebhookPermission;
use function App\Filament\Resources\checkReadWebhookPermission;

class ListWebhooks extends ListRecords
{
    protected static string $resource = WebhookResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->visible(function(){
    //             return checkCreateWebhookPermission();
    //         }),
    //     ];
    // }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadWebhookPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Webhooks",
            "activity" => "Viewed List Webhooks Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
