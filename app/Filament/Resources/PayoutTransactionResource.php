<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayoutTransactionResource\Pages;
use App\Filament\Resources\PayoutTransactionResource\RelationManagers;
use App\Models\Business;
use App\Models\PayoutTransaction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PayoutTransactionResource extends Resource
{
    protected static ?string $model = PayoutTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Payments';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->orderBy('updated_at', 'desc');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadPayoutTransactionsPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_id')
                    ->label('Business')
                    ->formatStateUsing(function($state){
                        return Business::where('id', $state)->first()->business_name;
                    }),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        "Initiated" => "Initiated",
                        "Authorised" => "Success",
                        "Cancelled" => "Cancelled",
                        "Failed" => "Failed"
                    ])
            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Download Payout Transaction')
                        ->visible(function (){
                        return checkCreatePayoutTransactionsPermission();
                    }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayoutTransactions::route('/'),
            'create' => Pages\CreatePayoutTransaction::route('/create'),
            'edit' => Pages\EditPayoutTransaction::route('/{record}/edit'),
        ];
    }
}
