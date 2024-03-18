<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use function App\Filament\Resources\checkCreateCustomersPermission;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateCustomersPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Customers",
            "activity" => "Viewed Create Customers Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
