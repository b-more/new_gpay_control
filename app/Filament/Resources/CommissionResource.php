<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommissionResource\Pages;
use App\Filament\Resources\CommissionResource\RelationManagers;
use App\Models\Business;
use App\Models\Commission;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CommissionResource extends Resource
{
    protected static ?string $model = Commission::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrows-right-left';

    protected static ?int $navigationSort = 10;

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
                Tables\Columns\TextColumn::make("business_id")
                    ->label('Business')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->formatStateUsing(function($state){
                        return Business::where('id',$state)->first()->business_name;
                    }),
                Tables\Columns\TextColumn::make("transaction_reference_number")
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make("cgrate_percentage")
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    }),
                Tables\Columns\TextColumn::make("geepay_percentage")
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    }),
                Tables\Columns\TextColumn::make("cgrate_fixed_charge")
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    }),
                Tables\Columns\TextColumn::make("geepay_fixed_charge")
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function($state){
                        return number_format($state,2);
                    }),
            ])
            ->filters([

            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Download Commissions Report'),
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
            'index' => Pages\ListCommissions::route('/'),
            //'create' => Pages\CreateCommission::route('/create'),
            //'edit' => Pages\EditCommission::route('/{record}/edit'),
        ];
    }
}
