<?php

namespace App\Filament\Resources\BankNameResource\Pages;

use App\Filament\Resources\BankNameResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateBankNamesPermission;

class CreateBankName extends CreateRecord
{
    protected static string $resource = BankNameResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateBankNamesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Bank Names",
            "activity" => "Viewed Create Bank Names Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }
}
