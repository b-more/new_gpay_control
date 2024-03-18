<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShowMaxPackageResource\Pages;
use App\Filament\Resources\ShowMaxPackageResource\RelationManagers;
use App\Models\ShowMaxPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShowMaxPackageResource extends Resource
{
    protected static ?string $model = ShowMaxPackage::class;

    protected static ?string $navigationIcon = 'heroicon-m-computer-desktop';

    protected static ?string $navigationGroup = 'Utilities';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadShowMaxPackagesPermission();
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdateShowMaxPackagesPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function (){
                        return checkDeleteShowMaxPackagesPermission();
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
            'index' => Pages\ListShowMaxPackages::route('/'),
            'create' => Pages\CreateShowMaxPackage::route('/create'),
            'edit' => Pages\EditShowMaxPackage::route('/{record}/edit'),
        ];
    }
}
