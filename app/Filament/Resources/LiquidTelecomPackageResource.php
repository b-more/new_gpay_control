<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiquidTelecomPackageResource\Pages;
use App\Filament\Resources\LiquidTelecomPackageResource\RelationManagers;
use App\Models\LiquidTelecomPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiquidTelecomPackageResource extends Resource
{
    protected static ?string $model = LiquidTelecomPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Utilities';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadLiquidTelecomPackagesPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('voucher_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('voucher_value')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('voucher_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_fixed')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_id')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_fixed')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                    //->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                    //->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdateLiquidTelecomPackagesPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function (){
                        return checkDeleteLiquidTelecomPackagesPermission();
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
            'index' => Pages\ListLiquidTelecomPackages::route('/'),
            'create' => Pages\CreateLiquidTelecomPackage::route('/create'),
            'edit' => Pages\EditLiquidTelecomPackage::route('/{record}/edit'),
        ];
    }
}
