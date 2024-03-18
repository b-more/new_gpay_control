<?php

namespace App\Filament\Pages;

use App\Models\Business;
use App\Models\Consumer;
use App\Models\ConsumerBalance;
use App\Models\Deposit;
use App\Models\Dispute;
use App\Models\FlightBooking;
use App\Models\Payment;
use App\Models\Transfer;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use function Filament\Support\format_number;

class GeePayDash extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-s-home';

    protected static string $view = 'filament.pages.gee-pay-dash';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return "Dashboard";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Add Business')->icon('heroicon-o-plus')->action(function(){
                return redirect("businesses/create");
            })
        ];
    }

    public function __construct()
    {
        $this->total_businesses = $this->total_businesses();
        $this->pending_businesses = $this->pending_businesses();
        $this->active_businesses = $this->active_businesses();
        //$this->total_consumers = $this->total_consumers();
        //$this->pending_consumers = $this->pending_consumers();
        //$this->active_consumers = $this->active_consumers();
        $this->total_users = $this->total_users();
        $this->pending_users = $this->pending_users();
        $this->active_users = $this->active_users();
        //$this->total_disputes = $this->total_disputes();
        //$this->pending_disputes = $this->pending_disputes();
        //$this->active_disputes = $this->active_disputes();
        //$this->konse_konse_float = $this->konse_konse_balance();
        //$this->total_consumer_balances = $this->consumer_balances();
        //$this->total_deposit_balances = $this->total_deposit_balances();
    }

    // public function total_deposit_balances()
    // {
    //     $totals = Deposit::sum("new_balance");
    //     return "ZMW " .number_format($totals,2);
    // }

    public function total_businesses()
    {
        return Business::count();
    }

    public function pending_businesses()
    {
        return Business::where('is_active', 0)->count();
    }

    public function active_businesses()
    {
        return Business::where('is_active', 1)->count();
    }

    // public function total_consumers()
    // {
    //     return Consumer::count();
    // }

    // public function pending_consumers()
    // {
    //     return Consumer::where('is_active', 0)->count();
    // }

    // public function active_consumers()
    // {
    //     return Consumer::where('is_active', 1)->count();
    // }

    public function total_users()
    {
        return User::count();
    }

    public function pending_users()
    {
        return User::where('is_active', 0)->count();
    }

    public function active_users()
    {
        return User::where('is_active', 1)->count();
    }

    // public function total_disputes()
    // {
    //     return Dispute::count();
    // }

    // public function pending_disputes()
    // {
    //     return Dispute::where('is_refunded', 0)->count();
    // }

    // public function active_disputes()
    // {
    //     return Dispute::where('is_refunded', 1)->count();
    // }

    public function table(Table $table): Table
    {
        $payments = Payment::latest()->take(5);

        return $table
            ->query($payments)
            ->poll('10s')
            ->striped()
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('business_name')->label('Business Name')->wrap()->searchable()->sortable(),
                TextColumn::make('account_number')->label('Account Number')->searchable()->sortable(),
                ViewColumn::make('Channel')
                    ->view('tables.columns.m-n-o-column'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function($state){
                        if($state == 1  && $state == "1")
                        {
                            return "Pending";
                        }elseif($state == 2 && $state == "2")
                        {
                            return "Success";
                        }elseif($state == 0 && $state == "0")
                        {
                            return "Failed";
                        }
                    })
                    ->color(function($record){
                        if($record->status == 1 && $record->status == "1")
                        {
                            return "blue_badge";
                        }elseif ($record->status == 0 && $record->status == "0")
                        {
                            return "red_badge";
                        }elseif ($record->status == 2 && $record->status == "2")
                        {
                            return "success";
                        }
                    }),
                TextColumn::make('received_amount')
                    ->label('Received (ZMW)')
                    ->alignEnd(true)
                    ->formatStateUsing(fn (string $state): string => number_format($state,2))
                    ->searchable()->sortable(),
                TextColumn::make('commission_charged')
                    ->label('Commission (ZMW)')
                    ->alignEnd(true)
                    ->formatStateUsing(fn (string $state): string => number_format($state,2))
                    ->searchable()->sortable(),
                TextColumn::make('payout_amount')
                    ->label('Payout (ZMW)')
                    ->alignEnd(true)
                    ->formatStateUsing(fn (string $state): string => number_format($state,2))
                    ->weight(FontWeight::Bold)
                    ->copyable()
                    ->copyMessage('Payout copied')
                    ->copyMessageDuration(1500)
                    ->searchable()->sortable(),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                BulkActionGroup::make([

                ])
            ]);
    }

    // public function konse_konse_balance()
    // {
    //     //credentials
    //     $konse_konse_url = env("KONSE_KONSE_URL");
    //     $konse_konse_username = env("KONSE_KONSE_USERNAME");
    //     $konse_konse_password = env("KONSE_KONSE_PASSWORD");


    //     $curl = curl_init();

    //     $xmlPayload = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:kon="http://konik.cgrate.com">
    //         <soapenv:Header>
    //             <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" soapenv:mustUnderstand="1">
    //                 <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="' . $konse_konse_username . '">
    //                     <wsse:Username>' . $konse_konse_username . '</wsse:Username>
    //                     <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $konse_konse_password . '</wsse:Password>
    //                 </wsse:UsernameToken>
    //             </wsse:Security>
    //         </soapenv:Header>
    //         <soapenv:Body>
    //             <ns2:getAccountBalance xmlns:ns2="http://konik.cgrate.com"></ns2:getAccountBalance>
    //         </soapenv:Body>
    //     </soapenv:Envelope>';

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $konse_konse_url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => $xmlPayload,
    //         CURLOPT_HTTPHEADER => array(
    //             'Accept: application/soap+xml,application/dime,multipart/related,text/*',
    //             'Content-Type: text/xml',
    //             // Specify the appropriate SOAPAction value if required by the service
    //             'SOAPAction: ""',
    //         ),
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);

    //     Log::info("Raw Response", ["Result" => $response]);

    //     $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

    //     $xml->registerXPathNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
    //     $xml->registerXPathNamespace('ns2', 'http://konik.cgrate.com');

    //     $responseCode = (string)$xml->xpath('//env:Envelope/env:Body/ns2:getAccountBalanceResponse/return/responseCode')[0];

    //     if ($responseCode == '0') {
    //         $accountBalance = (string)$xml->xpath('//env:Envelope/env:Body/ns2:getAccountBalanceResponse/return/balance')[0];
    //         return "ZMW ".$accountBalance;
    //     } else {
    //         return "refresh page";
    //     }
    // }

    // public function consumer_balances()
    // {
    //     $total = ConsumerBalance::sum('balance');
    //     return "ZMW ".number_format($total, 2);

    // }

    public $total_businesses;
    public $pending_businesses;
    public $active_businesses;
    public $total_consumers;
    public $pending_consumers;
    public $active_consumers;
    public $total_users;
    public $pending_users;
    public $active_users;
    public $total_disputes;
    public $pending_disputes;
    public $active_disputes;
    public $konse_konse_float;
    public $total_consumer_balances;
    public $total_deposit_balances;
}
