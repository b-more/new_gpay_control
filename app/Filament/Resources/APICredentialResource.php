<?php

namespace App\Filament\Resources;

use App\Filament\Resources\APICredentialResource\Pages;
use App\Filament\Resources\APICredentialResource\RelationManagers;
use App\Jobs\NotificationsMail;
use App\Models\APICredential;
use App\Models\Business;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Ramsey\Uuid\Uuid;
use function App\Filament\Resources\BusinessResource\Pages\apiAccessToken;

class APICredentialResource extends Resource
{
    protected static ?string $model = APICredential::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'System Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadAPICredentialPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('business_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('secret_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_id')
                    ->label('Business')
                    ->formatStateUsing(function($state){
                        return Business::where('id', $state)->first()->business_name;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('environment')
                    ->badge()
                    ->color(function($record){
                        if($record->environment == "production")
                        {
                            return "success";
                        }else{
                            return "blue_badge";
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('secret_id')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Create /Update At')
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
                Tables\Actions\Action::make('Reset Credentials')
                    ->icon('heroicon-m-arrow-path-rounded-square')
                    ->color('blue_badge')
                    ->action(function($record){
                        $reset_count = $record->reset_count + 1;
                        //create new api token
                        $api_access_token = apiAccessToken();

                        //update business record
                        $update_access_token = APICredential::where('id',$record->id)->where('environment', $record->environment)->update([
                            "access_token" => Hash::make($api_access_token),
                            "reset_count" => $reset_count
                        ]);

                        //send notification/email to business account owner
                        //send app notification
                        Notification::make()
                            ->title('API Credentials Reset')
                            ->success()
                            ->body('Your api credentials reset have been successful. Check your email')
                            ->sendToDatabase(Client::where('business_id', $record->business_id)->get())
                            ->send();

                        $business = Business::where('id', $record->business_id)->first();

                        //send email notification to the business
                        $message_subject = "API Credentials Reset";
                        $account_owner_name = Client::where('id', $business->user_id)->first()->name;
                        $message_to_send = "Your api credentials reset have been successful. This is your new API Access Token is ".$api_access_token;
                        //send notification
                        NotificationsMail::dispatch($business->account_number, $account_owner_name, $message_to_send, $message_subject, $business->business_email);

                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reset Credentials')
                    ->modalDescription(function($record){
                        return 'Are you sure you would like to reset API credentials for '.Business::where('id',$record->business_id)->first()->business_name;
                    })
                    ->modalSubmitActionLabel('Yes, Reset')
                    ->visible(function (){
                    return checkUpdateAPICredentialPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->visible(function(){

                        return checkCreateAPICredentialPermission();
                    })
                        ->label('Download API Credentials Report'),
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
            'index' => Pages\ListAPICredentials::route('/'),
            'create' => Pages\CreateAPICredential::route('/create'),
            'edit' => Pages\EditAPICredential::route('/{record}/edit'),
        ];
    }
}
