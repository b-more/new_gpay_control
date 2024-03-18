<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityBillCategoryResource\Pages;
use App\Filament\Resources\UtilityBillCategoryResource\RelationManagers;
use App\Models\UtilityBillCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UtilityBillCategoryResource extends Resource
{
    protected static ?string $model = UtilityBillCategory::class;

    protected static ?string $navigationIcon = 'heroicon-m-shopping-bag';

    protected static ?string $navigationGroup = 'Utilities';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadUtilityBillCategoryPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
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
                    return checkUpdateUtilityBillCategoryPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function (){
                        return checkDeleteUtilityBillCategoryPermission();
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
            'index' => Pages\ListUtilityBillCategories::route('/'),
            'create' => Pages\CreateUtilityBillCategory::route('/create'),
            'edit' => Pages\EditUtilityBillCategory::route('/{record}/edit'),
        ];
    }
}
