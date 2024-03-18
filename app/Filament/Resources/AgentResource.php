<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\Agent;
use App\Models\AuditTrail;
use App\Models\District;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

function sendAgentSmsNotification(string $message, string $phone_number): void
{
    // Send confirmation SMS
    $url_encoded_message = urlencode($message);

    $url = 'https://www.cloudservicezm.com/smsservice/httpapi?username=Blessmore&password=Blessmore&msg=' . $url_encoded_message . '.+&shortcode=2343&sender_id=GeePay Agent&phone=' . $phone_number . '&api_key=121231313213123123';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Use this only if you have SSL verification issues
    $response = curl_exec($ch);
    curl_close($ch);
}

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'KYC Agents';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadAgentsPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('KYC Agent')
                    ->description('Create an account for a KYC agent')
                    ->aside()
                    ->schema([
                        FileUpload::make('image')
                            ->label('Profile Picture')
                            ->directory('profile_pics')
                            ->avatar()
                            ->required()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '5:4'
                            ])
                            ->columnSpan('full'),
                        TextInput::make('name')->required(),
                        TextInput::make('email')->unique(ignoreRecord: true)->required(),
                        TextInput::make('phone_number')
                            ->unique(ignoreRecord: true)
                            ->length(10)
                            ->prefix('+26')
                            ->required(),
                        Select::make('province_id')
                            ->label('Province')
                            ->options(Province::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->required(),
                        Select::make('district_id')
                            ->label('District')
                            ->options(function (callable $get) {
                                $province = Province::find($get('province_id'));
                                if (!$province) {
                                    return District::all()->pluck('name', 'id');
                                }
                                return District::where('province_id', $province->id)->pluck('name', 'id');
                            })
                            ->reactive()
                            ->required(),
                        TextInput::make('password')
                            ->minLength(8)
                            ->prefix('Password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrateStateUsing(static fn(null|string $state): null|string => filled($state) ? Hash::make($state) : null)
                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateAgent)
                            ->dehydrated(static fn(null|string $state): bool => filled($state))
                            ->label(static fn(Page $livewire):  string =>
                            ($livewire instanceof EditUser) ? 'New Password' : 'password'
                            ),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
             Tables\Columns\ImageColumn::make('image')
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('province.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('hyper')
                        ->visible(function(){
                            return checkUpdateAgentsPermission();
                        }),
                    Tables\Actions\Action::make('Activate')
                        ->action(function ($record){
                            //change is_active column for Business and related Client accounts
                            $activate_agent = Agent::where('id', $record->id)->update([
                                "is_active" => 1
                            ]);

                            //log user activity
                            $activity = AuditTrail::create([
                                "user_id" => Auth::user()->id,
                                "module" => "Agents",
                                "activity" => "Activated KYC Agent record with ID ".$record->id,
                                "ip_address" => request()->ip()
                            ]);

                            $activity->save();

                            //send sms notification
                            $message = "Hi ".$record->name.", your agent account has been activated successfully. You can now onboard clients to GeePay platform.";
                            sendAgentSmsNotification($message,$record->phone_number);
                        })
                        ->color('success')
                        ->icon('heroicon-m-hand-thumb-up')
                        ->requiresConfirmation()
                        ->modalHeading('Activate KYC Agent')
                        ->modalDescription(function($record){
                            return 'Are you sure you would like to activate this '.$record->name.' agent';
                        })
                        ->modalSubmitActionLabel('Yes, Approve')
                        ->visible(function($record){
                            return checkUpdateAgentsPermission() && $record->is_active == 0;
                        }),
                    Tables\Actions\Action::make('Deactivate')
                        ->action(function ($record){
                            //change is_active column for Business and related Client accounts
                            $deactivate_agent = Agent::where('id', $record->id)->update([
                                "is_active" => 0
                            ]);

                            //log user activity
                            $activity = AuditTrail::create([
                                "user_id" => Auth::user()->id,
                                "module" => "Agents",
                                "activity" => "Deactivated Agent record with ID ".$record->id,
                                "ip_address" => request()->ip()
                            ]);

                            $activity->save();

                            //send sms notification
                            $message = "Hi ".$record->name.", your agent account has been deactivated. You are currently not allowed to onboard clients to GeePay platform.";
                            sendAgentSmsNotification($message,$record->phone_number);
                        })
                        ->color('danger')
                        ->icon('heroicon-m-hand-thumb-down')
                        ->requiresConfirmation()
                        ->modalHeading('Deactivate Agent')
                        ->modalDescription(function($record){
                            return 'Are you sure you want to deactivate '. $record->name." agent?";
                        })
                        ->modalSubmitActionLabel('Yes, Deactivate')
                        ->visible(function($record){
                            return checkUpdateAgentsPermission() && $record->is_active == 1;
                        })
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->visible(function(){
                            return checkCreateAgentsPermission();
                        })
                    ->label("Agent  Report Download")
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
