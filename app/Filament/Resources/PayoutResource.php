<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayoutResource\Pages;
use App\Filament\Resources\PayoutResource\RelationManagers;
use App\Models\Payout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;

    protected static ?string $navigationIcon = 'heroicon-s-banknotes';

    protected static ?int $navigationSort = 9;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->orderBy('updated_at', 'desc');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadPayoutsPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('old_balance')
                    ->label('Old Balance (ZMW)')
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->alignEnd()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('new_balance')
                    ->label('New Balance (ZMW)')
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    })
                    ->searchable()
                    ->alignEnd()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Balances'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created/Updated At')
                    ->dateTime()
                    ->description(function($record){
                        return $record->updated_at;
                    })
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('hyper')
                    ->label("Payout")
                    ->visible(function (){
                    return checkUpdatePayoutsPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->visible(function (){
                        return checkCreateCustomersPermission();
                    })
                        ->label('Download Payout Report')
                       ,
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PayoutRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayouts::route('/'),
            //'create' => Pages\CreatePayout::route('/create'),
            'edit' => Pages\EditPayout::route('/{record}/edit'),
        ];
    }
}
