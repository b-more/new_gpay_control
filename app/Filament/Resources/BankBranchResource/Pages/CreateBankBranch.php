<?php

namespace App\Filament\Resources\BankBranchResource\Pages;

use App\Filament\Resources\BankBranchResource;
use App\Models\AuditTrail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

use function App\Filament\Resources\checkCreateBankBranchesPermission;

class CreateBankBranch extends CreateRecord
{
    protected static string $resource = BankBranchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless(checkCreateBankBranchesPermission(),403);

        $activity = AuditTrail::create([
            "user_id" => $user->id,
            "module" => "Bank Branches",
            "activity" => "Viewed Create Bank Branches Page",
            "ip_address" => request()->ip()
        ]);

        $activity->save();
    }

}
