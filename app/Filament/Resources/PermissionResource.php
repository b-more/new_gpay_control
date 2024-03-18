<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'User Management';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if(auth())
        {
            return $query->orderBy('created_at', 'desc')->where('is_active',1);
        }
        return $query->where('is_deleted', 0)->orderBy('created_at', 'desc')->where('is_active',1);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadPermissionsPermission();
    }

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
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('role.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('module')->sortable()->searchable(),
                Tables\Columns\ToggleColumn::make('create')->sortable()->searchable()
                    ->onColor('success')
                    ->offColor('danger'),
                Tables\Columns\ToggleColumn::make('read')->sortable()->searchable()
                    ->onColor('success')
                    ->offColor('danger'),
                Tables\Columns\ToggleColumn::make('update')->sortable()->searchable()
                    ->onColor('success')
                    ->offColor('danger'),
                Tables\Columns\ToggleColumn::make('delete')->sortable()->searchable()
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                SelectFilter::make('module')
                    ->label('Modules')
                    ->multiple()
                    ->options([
                        'Accumulative Balances' => 'Accumulative Balances',
                        'API Credentials' => 'API Credentials',
                        'Auth Activity Trails' => 'Auth Activity Trails',
                        'Business Category' => 'Business Category',
                        'Business Types' => 'Business Types',
                        'Client Audit Trails' => 'Client Audit Trails',
                        'Clients' => 'Clients',
                        'Commission Received' => 'Commission Received',
                        'Commissions' => 'Commissions',
                        'Consumer Balances' => 'Consumer Balances',
                        'Consumer Commissions' => 'Consumer Commissions',
                        'Consumer Commission Structure' => 'Consumer Commission Structure',
                        'Consumer Current Balance Limits' => 'Consumer Current Balance Limit',
                        'Consumer Daily Withdrawal Limits' => 'Consumer Daily Withdrawal Limits',
                        'Consumers' => 'Consumers',
                        'Consumer Transactions' => 'Consumer Transactions',
                        'Countries' => 'Countries',
                        'Current Balance' => 'Current Balance',
                        'Customers' => 'Customers',
                        'Deposits' => 'Deposits',
                        'Deposit Transactions' => 'Deposit Transactions',
                        'Disputes' => 'Disputes',
                        'Districts' => 'Districts',
                        'DSTV Packages' => 'DSTV Packages',
                        'Front-end URLs' => 'Front-end URLs',
                        'GoTV Packages' => 'GoTV Packages',
                        'Liquid Telecom Packages' => 'Liquid Telecom Packages',
                        'NRC Details' => 'NRC Details',
                        'Payment Links' => 'Payment Links',
                        'Payments' => 'Payments',
                        'Payouts' => 'Payouts',
                        'Payout Transactions' => 'Payout Transactions',
                        'Permissions' => 'Permissions',
                        'Provinces' => 'Provinces',
                        'Refunds' => 'Refunds',
                        'Reports' => 'Reports',
                        'Report Types' => 'Report Types',
                        'Roles' => 'Roles',
                        'Security Red Flags' => 'Security Red Flags',
                        'ShowMax Packages' => 'ShowMax Packages',
                        'Statuses' => 'Statuses',
                        'TopStar Packages' => 'TopStar Packages',
                        'Transfers' => 'Transfers',
                        'Two Factors' => 'Two Factors',
                        'Users' => 'Users',
                        'User Types' => 'User Types',
                        'Utility Bill Categories' => 'Utility Bill Categories',
                        'Utility Bills' => 'Utility Bills',
                        'Webhooks' => 'Webhooks'
                     ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdatePermissionsPermission();
                }),
            ])
            ->bulkActions([
               Tables\Actions\BulkActionGroup::make([
                   Tables\Actions\DeleteBulkAction::make()->visible(function (){
                    return checkDeletePermissionsPermission();
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
