<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessCategoryResource\Pages;
use App\Filament\Resources\BusinessCategoryResource\RelationManagers;
use App\Models\BusinessCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessCategoryResource extends Resource
{
    protected static ?string $model = BusinessCategory::class;

    protected static ?string $navigationIcon = 'heroicon-m-bars-3';

    protected static ?string $navigationGroup = 'Business Settings';

    public static function shouldRegisterNavigation(): bool
    {
       // return checkReadBusinessCategoryPermission();
       return false;
    }

    


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                   // ->toggleable(isToggledHiddenByDefault: true),
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
                    return checkUpdateBusinessCategoryPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function (){
                        return checkDeleteBusinessCategoryPermission();
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
            'index' => Pages\ListBusinessCategories::route('/'),
            'create' => Pages\CreateBusinessCategory::route('/create'),
            'edit' => Pages\EditBusinessCategory::route('/{record}/edit'),
        ];
    }
}
