<?php

namespace App\Filament\Resources\APICredentialResource\Pages;

use App\Filament\Resources\APICredentialResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateAPICredentialPermission;
use function App\Filament\Resources\checkReadAPICredentialPermission;

class ListAPICredentials extends ListRecords
{
    protected static string $resource = APICredentialResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->visible(function(){
    //             return checkCreateAPICredentialPermission();
    //         }),
    //     ];
    // }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadAPICredentialPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "API Credentials",
            "activity" => "Viewed List API Credentials Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
