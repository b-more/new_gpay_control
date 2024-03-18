<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentCommissionResource\Pages;
use App\Filament\Resources\PaymentCommissionResource\RelationManagers;
use App\Models\PaymentCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentCommissionResource extends Resource
{
    protected static ?string $model = PaymentCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Commission Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadPaymentCommissionsPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Fill Commission')
            ->description('Kindly provide the Payment Commission Details')
            ->aside()
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('category')->required(),
                TextInput::make('cgrate_percentage')->required(),
                TextInput::make('geepay_percentage')->required(),
                TextInput::make('cgrate_fixed_charge')->required(),
                TextInput::make('geepay_fixed_charge')->required(),
                Textarea::make('description')->required(),
                TextInput::make("is_active")->required()->default(1)
            ])
        ]);
            // ->schema([
            //     Forms\Components\TextInput::make('name')
            //         ->required()
            //         ->maxLength(255),
            //     Forms\Components\TextInput::make('category')
            //         ->required()
            //         ->maxLength(255),
            //     Forms\Components\Textarea::make('description')
            //         ->required()
            //         ->maxLength(65535)
            //         ->columnSpanFull(),
            //     Forms\Components\TextInput::make('calculations')
            //         ->required()
            //         ->maxLength(255),
            // ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cgrate_percentage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('geepay_percentage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cgrate_fixed_charge')
                    ->searchable(),
                Tables\Columns\TextColumn::make('geepay_fixed_charge')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Is Active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_deleted')
                    ->label('Is Deleted')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                    //->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdatePaymentCommissionsPermission();
                })
            ,
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Download Payment Commission Report')
                        ->visible(function (){
                        return checkDeletePaymentCommissionsPermission();
                    })
                ,
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
            'index' => Pages\ListPaymentCommissions::route('/'),
            'create' => Pages\CreatePaymentCommission::route('/create'),
            'edit' => Pages\EditPaymentCommission::route('/{record}/edit'),
        ];
    }
}
