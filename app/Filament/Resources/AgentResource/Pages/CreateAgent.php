<?php

namespace App\Filament\Resources\AgentResource\Pages;

use App\Filament\Resources\AgentResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateAgentsPermission;

class CreateAgent extends CreateRecord
{
    protected static string $resource = AgentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateAgentsPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Agents",
            "activity" => "Viewed Create Agents Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }


}
