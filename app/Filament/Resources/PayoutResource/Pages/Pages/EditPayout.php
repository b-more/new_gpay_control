<?php

namespace App\Filament\Resources\PayoutResource\Pages;

use App\Filament\Resources\PayoutResource;
use App\Models\Business;
use App\Models\Deposit;
use App\Models\Payout;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Contracts\Support\Htmlable;
use function App\Filament\Resources\checkDeletePayoutsPermission;

class EditPayout extends EditRecord
{
    protected static string $resource = PayoutResource::class;

    function extractIdFromString($inputString) {
        // Define a regular expression pattern to match the desired integer
        $pattern = '/\/payouts\/(\d+)\/edit/';

        // Use preg_match to search for the pattern in the input string
        $matches = [];
        if (preg_match($pattern, $inputString, $matches)) {
            // Extract the integer value from the match
            $extractedId = (int)$matches[1];
            return $extractedId;
        } else {
            // If no match was found, return an appropriate message or value
            return null;
        }
    }

    public function getTitle(): string
    {
        $id = $this->extractIdFromString(request());
        $payout = Payout::where("id", $id)->first();
        $business_name = Business::where("id", $payout->business_id)->first()->business_name;
        return "Paying out to ".$business_name;
    }

    public function getSubheading(): string|Htmlable|null
    {
        $id = $this->extractIdFromString(request());
        $payout = Payout::where("id", $id)->first();

        return "Amount ZMW ".number_format($payout->new_balance,2);
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getFormActions(): array
    {
        return [

        ];
    }
}
