<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateCustomersPermission;
use function App\Filament\Resources\checkReadCustomersPermission;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*Actions\CreateAction::make()->visible(function(){
                return checkCreateCustomersPermission();
            }),*/
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkReadCustomersPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Customers",
            "activity" => "Viewed List Customers Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
