<?php

namespace App\Filament\Resources\PayoutResource\RelationManagers;

use App\Jobs\NotificationsMail;
use App\Models\Business;
use App\Models\Client;
use App\Models\Commission;
use App\Models\PaymentCommission;
use App\Models\Payout;
use App\Models\PayoutTransaction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use function App\Filament\Resources\checkCreatePayoutAuthorisePermission;
use function App\Filament\Resources\checkCreatePayoutCancelPermission;
use function App\Filament\Resources\checkCreatePayoutConfirmPermission;
use function App\Filament\Resources\checkCreatePayoutFailedPermission;
use function App\Filament\Resources\checkUpdateDepositsPermission;
use function App\Filament\Resources\checkUpdateDepositTransactionsPermission;
use function App\Filament\Resources\checkUpdatePayoutsPermission;
use function App\Filament\Resources\checkUpdatePayoutTransactionsPermission;

class PayoutRelationManager extends RelationManager
{
    protected static string $relationship = 'payout';

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

    function generatePayoutReferenceNumber() {
        $prefix = 'PO'; // Prefix for the account number
        $suffix = time(); // Suffix for the account number (UNIX timestamp)

        // Generate a random number between 1000 and 9999
        $random = rand(100000, 999999);

        // Combine the prefix, random number, and suffix to form the account number
        $raw_payment_reference_number = $prefix . $random . $suffix;

        $payout_reference_number = substr($raw_payment_reference_number, 0, 24);

        // Check if the account number already exists in the database
        if (DB::table('payout_transactions')->where('internal_reference_number', $payout_reference_number)->exists()) {
            // If the account number already exists, generate a new one recursively
            return $this->generatePayoutReferenceNumber();
        }

        return $payout_reference_number;
    }

    public function form(Form $form): Form
    {
        $id = $this->extractIdFromString(request());
        $payout = Payout::where('id',$id)->first();
        $business = Business::where('id',$payout->business_id)->first();
        $payout_transaction_initiated_count = PayoutTransaction::where('business_id',$business->id)->where('status','Initiated')->count();
        $payout_transaction_authorised_count = PayoutTransaction::where('business_id',$business->id)->where('status','Authorised')->count();

        //calculations
        $charge_details = PaymentCommission::where("category", "Payout")->first();

        //add GeePay percentage, then mu
        $convenience_fee = (((floatval($charge_details->cgrate_percentage) + floatval($charge_details->geepay_percentage))/100) * floatval($payout->new_balance)) + (floatval($charge_details->cgrate_fixed_charge) + floatval($charge_details->geepay_fixed_charge));

        $total_amount = floatval($payout->new_balance) - $convenience_fee;

        $internal_payment_reference_number = $this->generatePayoutReferenceNumber();

        if($payout->new_balance < "1" && $payout->new_balance < 1 && $payout_transaction_initiated_count > 0 && $payout_transaction_authorised_count > 0)
        {
            return $form
                ->schema([
                ]);
        }
        return $form
            ->schema([
                Forms\Components\Section::make('Initiate Payout')
                    ->description('')
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->prefix('ZMW')
                            ->default(number_format($payout->new_balance,2))
                            ->disabled(),
                        Forms\Components\Hidden::make('amount')
                            ->default($payout->new_balance),
                        Forms\Components\TextInput::make('transaction_charge')
                            ->label('Transaction Charge')
                            ->prefix('ZMW')
                            ->default(number_format($convenience_fee,2))
                            ->disabled(),
                        Forms\Components\Hidden::make('transaction_charge')
                            ->default($convenience_fee),
                        Forms\Components\TextInput::make('amount_payable')
                            ->label('Payable Amount')
                            ->prefix('ZMW')
                            ->default(number_format($total_amount,2))
                            ->disabled(),
                        Forms\Components\Hidden::make('amount_payable')
                            ->default($total_amount),
                        Forms\Components\Hidden::make('payout_id')
                            ->default($id),
                        Forms\Components\Hidden::make('business_id')
                            ->default($business->id),
                        Forms\Components\Hidden::make('internal_reference_number')
                            ->default($internal_payment_reference_number),
                        Forms\Components\Hidden::make('initiated_by')
                            ->default(auth()->user()->id),
                        Forms\Components\Hidden::make('status')
                            ->default("Initiated"),
                        Forms\Components\Hidden::make('initiated_at')
                            ->default(now()),
                        Forms\Components\Hidden::make('business_bank_account_number')
                            ->default($business->business_bank_account_number),
                        Forms\Components\Hidden::make('business_bank_account_name')
                            ->default($business->business_bank_account_name),
                        Forms\Components\Hidden::make('business_bank_account_branch_name')
                            ->default($business->business_bank_account_branch_name),
                        Forms\Components\Hidden::make('business_bank_account_branch_code')
                            ->default($business->business_bank_account_branch_code),
                        Forms\Components\Hidden::make('business_bank_account_sort_code')
                            ->default($business->business_bank_account_sort_code),
                        Forms\Components\Hidden::make('business_bank_account_swift_code')
                            ->default($business->business_bank_account_swift_code)
                    ])
            ]);
    }

    protected function afterCreate(): void
    {
        $payout = Payout::where('id', $this->record->payout_id)->first();

        //calculations
        $charge_details = PaymentCommission::where("category", "Payout")->first();

        //define definite commission for each
        $cgrate_percentage = (floatval($charge_details->cgrate_percentage)/100) * floatval($payout->new_balance);
        $geepay_percentage = (floatval($charge_details->geepay_percentage)/100) * floatval($payout->new_balance);
        $cgrate_fixed_charge = floatval($charge_details->cgrate_fixed_charge);
        $geepay_fixed_charge = floatval($charge_details->geepay_fixed_charge);

        // save the commissions
        $new_comission_record = Commission::create([
            "business_id" => $this->record->business_id,
            "transaction_reference_number" => $this->record->internal_payment_reference_number,
            "cgrate_percentage" => $cgrate_percentage,
            "geepay_percentage" => $geepay_percentage,
            "cgrate_fixed_charge" => $cgrate_fixed_charge,
            "geepay_fixed_charge" => $geepay_fixed_charge
        ]);

        $new_comission_record->save();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label("Requested(ZMW)")
                    ->formatStateUsing(function($state){
                       return number_format($state,2);
                    })
                    ->wrapHeader()
                    ->sortable()
                    ->alignEnd()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_charge')
                    ->label("Charge(ZMW)")
                    ->wrapHeader()
                    ->sortable()
                    ->alignEnd()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_payable')
                    ->label('Payable(ZMW)')
                    ->wrapHeader()
                    ->alignEnd()
                    ->sortable()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function($record){
                        if($record->status == "Initiated")
                        {
                            return "blue_badge";
                        }elseif ($record->status == "Authorised")
                        {
                            return "purple_badge";
                        }elseif ($record->status == "Success")
                        {
                            return "success";
                        }elseif ($record->status == "Cancelled")
                        {
                            return "red_badge";
                        }elseif ($record->status == "Failed")
                        {
                            return "red_badge";
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('internal_reference_number')
                    ->label('Internal/External Ref No')
                    ->wrapHeader()
                    ->description(function($record){
                        return $record->bank_reference_number;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_bank_account_number')
                    ->searchable()
                    ->sortable()
                    ->label('Bank Name/Account')
                    ->formatStateUsing(function($record){
                        return Business::where('id',$record->business_id)->first()->business_bank_name;
                    })
                    ->description(function($record){
                        return $record->business_bank_account_number;
                    }),
                Tables\Columns\TextColumn::make('initiated_by')
                    ->label('Initiated By/At')
                    ->formatStateUsing(function($state){
                        return User::where('id', $state)->first()->name;
                    })
                    ->description(function($record){
                        return $record->initiated_at;
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(function(){
                        $id = $this->extractIdFromString(request());
                        $payout = Payout::where('id',$id)->first();
                        $business = Business::where('id',$payout->business_id)->first();
                        $payout_transaction_initiated_count = PayoutTransaction::where('business_id',$business->id)->where('status','Initiated')->count();
                        $payout_transaction_authorised_count = PayoutTransaction::where('business_id',$business->id)->where('status','Authorised')->count();
                        return $payout->new_balance > 1 && $payout_transaction_initiated_count == 0  && $payout_transaction_authorised_count == 0;
                    }),
            ])
            ->actions([
                ActionGroup::make([
                Tables\Actions\Action::make('Authorised')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->action(function($record){
                        //authorise payout
                        $old_balance = Payout::where('id',$record->payout_id)->first()->new_balance;
                        $new_balance = floatval($old_balance) - floatval($record->amount);

                        $update_payout_transaction = PayoutTransaction::where('id', $record->id)->update([
                            "status" => "Authorised",
                            "authorised_by" => auth()->user()->id,
                            "old_balance" => $old_balance,
                            "new_balance" => $new_balance,
                            "authorised_at" => now()
                        ]);

                        $update_payout = Payout::where('id', $record->payout_id)->update([
                            "old_balance" => $old_balance,
                            "new_balance" => $new_balance
                        ]);

                        $business = Business::where('id', $record->business_id)->first();

                        //send email notification to the business
                        $message_subject = "ZMW".number_format($record->amount_payable,2)." payout on the way";
                        $account_owner_name = Client::where('id', $business->user_id)->first()->name;
                        $message_to_send = "Your ".$business->business_name." account number ".$business->account_number." payout of ZMW".number_format($record->amount_payable,2)." is being processed. We will notify you as soon as the transaction is completed";
                        //send notification
                        NotificationsMail::dispatch($business->account_number, $account_owner_name, $message_to_send, $message_subject, $business->business_email);
                    })
                    ->visible(function ($record){
                        return checkCreatePayoutAuthorisePermission() && checkUpdatePayoutsPermission() && checkUpdatePayoutTransactionsPermission() && $record->status == "Initiated";
                    }),
                    Tables\Actions\Action::make('Cancel')
                        ->icon('heroicon-s-x-circle')
                        ->color('red_badge')
                        ->action(function($record){
                            $update_payout_transaction = PayoutTransaction::where('id', $record->id)->update([
                                "status" => "Cancelled",
                                "authorised_by" => auth()->user()->id,
                                "authorised_at" => now()
                            ]);
                        })
                        ->visible(function ($record){
                            return checkCreatePayoutCancelPermission() && checkUpdatePayoutsPermission() && checkUpdatePayoutTransactionsPermission() && $record->status == "Initiated";
                        }),
                    Tables\Actions\Action::make('Confirm')
                        ->icon('heroicon-s-check-circle')
                        ->color('success')
                        ->action(function($record, array $data){
                            $update_payout_transaction = PayoutTransaction::where('id', $record->id)->update([
                                "status" => "Success",
                                "confirmed_by" => auth()->user()->id,
                                "confirmed_at" => now(),
                                "transaction_method" => "manual",
                                "bank_reference_number" => $data["bank_reference_number"]
                            ]);

                            $business = Business::where('id', $record->business_id)->first();

                            //send email notification to the business
                            $message_subject = "ZMW".number_format($record->amount_payable,2)." Payout Successful";
                            $account_owner_name = Client::where('id', $business->user_id)->first()->name;
                            $message_to_send = "Your ".$business->business_name." account number ".$business->account_number." payout of ZMW".number_format($record->amount_payable,2)." to your bank account ending with ".substr($business->business_bank_account_number, -4)." (".$business->business_bank_account_name.") has been successful and confirmed.";
                            //send notification
                            NotificationsMail::dispatch($business->account_number, $account_owner_name, $message_to_send, $message_subject, $business->business_email);
                        })
                        ->form([
                            Forms\Components\TextInput::make('bank_reference_number')
                                ->label('Bank Reference Number')
                                ->required(),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Confirm Payout')
                        ->modalDescription(function($record){
                            return 'Are you sure you want to confirm this payout transaction';
                        })
                        ->modalSubmitActionLabel('Yes, Confirm')
                        ->visible(function ($record){
                            return checkCreatePayoutConfirmPermission() && checkUpdatePayoutsPermission() && checkUpdatePayoutTransactionsPermission() && $record->status == "Authorised";
                        }),
                    Tables\Actions\Action::make('Failed')
                        ->icon('heroicon-s-x-circle')
                        ->color('red_badge')
                        ->action(function($record, array $data){
                            $update_payout_transaction = PayoutTransaction::where('id', $record->id)->update([
                                "status" => "Failed",
                                "confirmed_by" => auth()->user()->id,
                                "confirmed_at" => now(),
                                "remarks" => $data["remarks"]
                            ]);

                            $old_balance = Payout::where('id', $record->payout_id)->first()->new_balance;
                            $new_balance = $old_balance + $record->amount;

                            $update_payout = Payout::where('id', $record->payout_id)->update([
                                "old_balance" => $old_balance,
                                "new_balance" => $new_balance
                            ]);

                            $business = Business::where('id', $record->business_id)->first();

                            //send email notification to the business
                            $message_subject = "ZMW".number_format($record->amount_payable,2)." Payout Successful";
                            $account_owner_name = Client::where('id', $business->user_id)->first()->name;
                            $message_to_send = "Your ".$business->business_name." account number ".$business->account_number." payout of ZMW".number_format($record->amount_payable,2)." to your bank account ending with ".substr($business->business_bank_account_number, -4)." (".$business->business_bank_account_name.") has been reversed due to network failure. We will re-issue the payout soon.";
                            //send notification
                            NotificationsMail::dispatch($business->account_number, $account_owner_name, $message_to_send, $message_subject, $business->business_email);
                        })
                        ->form([
                            Forms\Components\TextInput::make('remarks')
                                ->label('Failure Reason')
                                ->required(),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Failed Payout')
                        ->modalDescription(function($record){
                            return 'Are you sure this payout transaction has failed?';
                        })
                        ->modalSubmitActionLabel('Yes, Failed')
                        ->visible(function ($record){
                            return checkCreatePayoutFailedPermission() && checkUpdatePayoutsPermission() && checkUpdatePayoutTransactionsPermission() && $record->status == "Authorised";
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                ]),
            ]);
    }
}
