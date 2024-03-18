<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DsTvPackageResource\Pages;
use App\Filament\Resources\DsTvPackageResource\RelationManagers;
use App\Models\DstvPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DsTvPackageResource extends Resource
{
    protected static ?string $model = DstvPackage::class;

    protected static ?string $navigationIcon = 'heroicon-s-tv';

    protected static ?string $navigationGroup = 'Utilities';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadDSTVPackagesPermission();
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
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_value')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_fixed')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdateDSTVPackagesPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function (){
                        return checkDeleteDSTVPackagesPermission();
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
            'index' => Pages\ListDsTvPackages::route('/'),
            'create' => Pages\CreateDsTvPackage::route('/create'),
            'edit' => Pages\EditDsTvPackage::route('/{record}/edit'),
        ];
    }
}
