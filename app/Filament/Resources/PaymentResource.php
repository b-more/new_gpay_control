<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 7;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if(auth()->user()->role_id == 1)
        {
            return $query->orderBy('created_at', 'desc');
        }
        return $query->where('is_deleted', 0)->orderBy('created_at', 'desc');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadPaymentsPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('business_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('customer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('payment_method_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_channel')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_reference_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('txn_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('received_amount')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('commission_charged')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('payout_amount')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('short_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('long_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('is_deleted')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('is_refunded')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->sortable()
                    ->description(function ($record){
                        return $record->account_number;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Customer')
                    ->sortable()
                    ->description(function($record){
                        return Customer::where('phone_number', $record->phone_number)->first()->name ?? "";
                    })
                    ->searchable(),
                ViewColumn::make('Channel')
                    ->view('tables.columns.m-n-o-column'),
                Tables\Columns\TextColumn::make('payment_reference_number')
                    ->sortable()
                    ->label('Ref Number/Txn')
                    ->searchable()
                    ->description(function($record){
                        return $record->txn_number;
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('received_amount')
                    ->label('Received Amount(ZMW)')
                    ->alignEnd()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->searchable()
                    ->summarize(Sum::make()->label('Total Received')),
                Tables\Columns\TextColumn::make('commission_charged')
                    ->label('Commission Charged(ZMW)')
                    ->alignEnd()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payout_amount')
                    ->label('Payout Amount(ZMW)')
                    ->alignEnd()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->searchable(),
                Tables\Columns\ViewColumn::make('receipt')
                    ->label('Receipt Link')
                    ->view('tables.columns.url-column')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function($state){
                        if($state == 1  && $state == "1")
                        {
                            return "Pending";
                        }elseif($state == 2 && $state == "2")
                        {
                            return "Success";
                        }elseif($state == 3 && $state == "3")
                        {
                            return "Refunded";
                        }
                        elseif($state == 0 && $state == "0")
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
                        }elseif ($record->status == 3 && $record->status == "3")
                        {
                            return "purple_badge";
                        }elseif ($record->status == 2 && $record->status == "2")
                        {
                            return "success";
                        }
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_deleted')
                    ->label('Deleted')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_refunded')
                    ->label('Refunded')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created/Updated At')
                    ->dateTime()
                    ->description(function($record){
                        return $record->updated_at;
                    })
                    ->sortable(),
                TextColumn::make('commission_charged')
                    ->alignEnd()
                    ->formatStateUsing(function ($state){
                        return number_format($state,2);
                    })
                    ->summarize(Sum::make()->label('Total Commissions')),
                    TextColumn::make('payout_amount')
                        ->alignEnd()
                        ->formatStateUsing(function ($state){
                            return number_format($state,2);
                        })
                        ->summarize(Sum::make()->label('Total Payouts'))
            ])
            ->filters([
                SelectFilter::make('payment_channel')
                    ->multiple()
                    ->options([
                        'Airtel Money' => 'Airtel Money',
                        'MTN Money' => 'MTN Money',
                        'Zamtel Money' => 'Zamtel Money'
                    ]),
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        '1' => 'Pending',
                        '2' => 'Success',
                        '0' => 'Failed',
                        '3' => 'Refunded'
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                /*Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdatePaymentsPermission();
                }),*/
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Download Payment Report')
                        ->visible(function (){
                        return checkCreatePaymentsPermission();
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
            'index' => Pages\ListPayments::route('/'),
            //'create' => Pages\CreatePayment::route('/create'),
            //'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
