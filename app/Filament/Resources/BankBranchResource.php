<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankBranchResource\Pages;
use App\Filament\Resources\BankBranchResource\RelationManagers;
use App\Models\BankBranch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankBranchResource extends Resource
{
    protected static ?string $model = BankBranch::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'System Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadBankBranchesPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bank_name_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('branch_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('closure_date')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bank_name_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('closure_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                  //  ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                  //  ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdateBankBranchesPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(function (){
                        return checkDeleteBankBranchesPermission();
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
            'index' => Pages\ListBankBranches::route('/'),
            'create' => Pages\CreateBankBranch::route('/create'),
            'edit' => Pages\EditBankBranch::route('/{record}/edit'),
        ];
    }
}
