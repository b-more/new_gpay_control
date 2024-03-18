<?php

namespace App\Filament\Resources;
use App\Jobs\NotificationsMail;
use App\Jobs\SendAccountMail;
use App\Models\APICredential;
use App\Models\AuditTrail;
use App\Models\Client;
use Filament\Forms\Components\Wizard;
use App\Filament\Resources\BusinessResource\Pages;
use App\Filament\Resources\BusinessResource\RelationManagers;
use App\Models\BankBranch;
use App\Models\BankName;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessType;
use App\Models\Country;
use App\Models\District;
use App\Models\PaymentCommission;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ExportBulkAction as ActionsExportBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Label;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Ramsey\Uuid\Uuid;

function apiAccessSecretId() {
    $secret_id = Uuid::uuid4();
    // Check if the secret id already exists in the database
    if (DB::table('a_p_i_credentials')->where('secret_id', $secret_id)->exists()) {
        // If the secret id already exists, generate a new one recursively
        return apiAccessSecretId();
    }
    return $secret_id;
}

function apiAccessToken() {
    $access_token = Uuid::uuid4();
    // Check if the secret id already exists in the database
    if (DB::table('a_p_i_credentials')->where('access_token', $access_token)->exists()) {
        // If the secret id already exists, generate a new one recursively
        return apiAccessToken();
    }
    return $access_token;
}

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if(auth()->user()->role_id == 1)
        {
            return $query->orderBy('created_at', 'desc');
        }
        return $query->where('is_deleted', 0)->orderBy('created_at', 'desc');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadBusinessesPermission();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Business Details')
                            ->schema([
                                Forms\Components\TextInput::make('account_owner_name')
                                    ->prefixIcon('heroicon-m-user')
                                    ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('business_name')
                                            ->prefixIcon('heroicon-o-building-office-2')
                                            ->unique(ignoreRecord: true)
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                        Forms\Components\TextInput::make('business_email')
                                            ->email()
                                            ->prefixIcon('heroicon-m-chat-bubble-bottom-center-text'),

                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        Select::make('business_category_id')
                                            ->label('Business Category')
                                            ->options(BusinessCategory::all()->pluck('name', 'id')->toArray())
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                        Select::make('business_type_id')
                                            ->label('Business Type')
                                            ->options(BusinessType::all()->pluck('name', 'id')->toArray())
                                            ->live()
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness)
                                    ]),
                                    // Forms\Components\Section::make('')
                                    // ->schema([
                                    //     FileUpload::make('Certificate of Incorporation')
                                    //         ->label('Profile Picture')
                                    //         ->directory('director_nrc')
                                    //         ->required()
                                    //         ->columnSpan('full')
                                    //         ->maxSize(1024),
                                    // ]),
                                Grid::make(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('business_tpin')
                                            ->prefix('TPIN')
                                            ->unique(ignoreRecord: true)
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 1 && $get('business_type_id') == "1"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('business_reg_number')
                                            ->prefix('PACRA')
                                            ->unique(ignoreRecord: true)
                                            ->Label('Pacra Number')
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                        Forms\Components\TextInput::make('business_tpin')
                                            ->prefix('TPIN')
                                            ->unique()
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 2 && $get('business_type_id') == "2"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('business_reg_number')
                                            ->prefix('Reg No')
                                            ->unique(ignoreRecord: true)
                                            ->Label('Reg Number')
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                        Forms\Components\TextInput::make('business_tpin')
                                            ->prefix('TPIN')
                                            ->unique(ignoreRecord: true)
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 3 && $get('business_type_id') == "3"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                Forms\Components\Section::make('Certificate of Incorporation')
                                    ->schema([
                                        FileUpload::make('certificate_of_incorporation')
                                            ->label('')
                                            ->directory('certificate_of_incorporation')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->multiple()
                                            ->storeFileNamesIn('certificate_of_incorporation')
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness)
                                            ->maxSize(1024),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 2 && $get('business_type_id') == "2" || $get('business_type_id')== 4 && $get('business_type_id') == "4"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                Forms\Components\Section::make('Certificate of Incorporation')
                                    ->schema([
                                        FileUpload::make('certificate_of_incorporation')
                                            ->label('Certificate of Registration')
                                            ->directory('certificate_of_incorporation')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->multiple()
                                            ->storeFileNamesIn('certificate_of_incorporation')
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness)
                                            ->maxSize(1024),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 3 && $get('business_type_id') == "3"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                Forms\Components\Section::make('Tax Clearance')
                                    ->schema([
                                        FileUpload::make('tax_clearance')
                                            ->label('')
                                            ->directory('tax_clearance')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->multiple()
                                            ->storeFileNamesIn('tax_clearance')
                                            ->maxSize(1024),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 1 && $get('business_type_id') == "1" || $get('business_type_id')== 4 && $get('business_type_id') == "4"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                Forms\Components\Section::make('Tax Clearance')
                                    ->schema([
                                        FileUpload::make('tax_clearance')
                                            ->label('')
                                            ->directory('tax_clearance')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->storeFileNamesIn('tax_clearance')
                                            ->multiple()
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness)
                                            ->maxSize(1024),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 3 && $get('business_type_id') == "3" || $get('business_type_id')== 2 && $get('business_type_id') == "2" || $get('business_type_id')== 4 && $get('business_type_id') == "4"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                    Forms\Components\Section::make('IDs Director|Trustee')
                                    ->schema([
                                        FileUpload::make('director_nrc')
                                            ->label('')
                                            ->directory('director_nrc')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->storeFileNamesIn('director_nrc')
                                            ->multiple()
                                            ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateBusiness)
                                            ->maxSize(1024),
                                    ])

                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 1 && $get('business_type_id') == "1" || $get('business_type_id')== 2 && $get('business_type_id') == "2" || $get('business_type_id')== 4 && $get('business_type_id') == "4"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                    Forms\Components\Section::make('Director Details')
                                    ->schema([
                                        FileUpload::make('director_details')
                                            ->label('')
                                            ->directory('director_details')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->storeFileNamesIn('director_details')
                                            ->multiple()
                                            ->required()
                                            ->maxSize(1024),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 1 && $get('business_type_id') == "1" || $get('business_type_id')== 2 && $get('business_type_id') == "2" || $get('business_type_id')== 4 && $get('business_type_id') == "4"){
                                            return true;
                                        }
                                        return false;
                                    }),
                                    Forms\Components\Section::make('Pacra Printout')
                                    ->schema([
                                        FileUpload::make('pacra_printout')
                                            ->label('')
                                            ->directory('pacra_printout')
                                            ->reorderable()
                                            ->openable()
                                            ->maxSize(5)
                                            ->storeFileNamesIn('pacra_printout')
                                            ->multiple()
                                            ->required()
                                            ->maxSize(1024),
                                    ])
                                    ->visible(function(callable $get){
                                        if($get('business_type_id')== 1 && $get('business_type_id') == "1" || $get('business_type_id')== 2 && $get('business_type_id') == "2" || $get('business_type_id')== 4 && $get('business_type_id') == "4"){
                                            return true;
                                        }
                                        return false;
                                    }),
                            ]),
                            Wizard\Step::make('Contact Details')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('business_address_line_1')
                                            ->prefixIcon('heroicon-o-book-open')
                                            ->label('Physical Business Address')
                                            ->required(),
                                        Forms\Components\TextInput::make('business_phone_number')
                                            ->length(9)
                                            ->prefix('+260')
                                            ->required(),
                                    ]),
                                Grid::make(2)
                                    ->schema([
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
                                    ]),
                            ]),
                            Wizard\Step::make('Bank Details')
                            ->schema([
                                Grid::make(1)
                                ->schema([
                                    TextInput::make('business_bank_account_name')
                                        ->required()

                                ]),
                                Grid::make(2)
                                    ->schema([
                                        Select::make('business_bank_name')
                                            ->label('Bank Name')
                                            ->options(BankName::all()->pluck('name', 'name')->toArray())
                                            ->live()
                                            ->required(),

                                        Select::make('business_bank_account_branch_name')
                                            ->label('Bank Branch Name')
                                            ->options(function (callable $get) {
                                                $bank = BankName::where('name',$get('business_bank_name'))->first();
                                                if (!$bank) {
                                                    return BankBranch::all()->pluck('branch_name', 'branch_name');
                                                }
                                                return BankBranch::where('bank_name_id', $bank->id)->pluck('branch_name', 'branch_name');
                                            })
                                            ->reactive(),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        /*Forms\Components\TextInput::make('business_bank_account_branch_code')
                                        ->prefixIcon('heroicon-m-building-office')
                                        ->label('Branch Code')
                                        ->required(),*/

                                        TextInput::make('business_bank_account_number')
                                        ->prefixIcon('heroicon-o-credit-card')
                                        ->label('Bank Account Number')
                                        ->required(),
                                    ]),

                            ]),
                            Wizard\Step::make('Callback Url')
                            ->schema([

                                /*Grid::make(2)
                                    ->schema([
                                        Select::make('collection_commission_id')
                                            ->prefixIcon('heroicon-m-chevron-double-down')
                                            ->options(PaymentCommission::where('category','collections')->pluck("name","id")->toArray())
                                            ->required(),
                                        Select::make('disbursement_commission_id')
                                            ->prefixIcon('heroicon-m-chevron-double-up')
                                            ->options(PaymentCommission::where('category','disbursement')->pluck("name","id")->toArray())

                                    ]),*/
                                    Grid::make(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('callback_url')
                                        ->prefixIcon('heroicon-m-link')
                                        ->label('Callback URL')
                                            ->url(),
                                    ])
                            ]),

                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Business Name')
                    ->wrap()
                    ->searchable()
                    ->description(function($record){
                        return $record->business_email;
                    }),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Acc Owner')
                    ->wrap()
                    ->formatStateUsing(function($state){
                        return Client::where('id', $state)->first()->name  ?? "";
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('province.name')
                    ->label('Province/District')
                    ->numeric()
                    ->sortable()
                    ->description(function($record){
                        return District::where('id', $record->district_id)->first()->name ?? "";
                    }),
                Tables\Columns\TextColumn::make('BusinessCategory.name')
                    ->label('Category/Type')
                    ->numeric()
                    ->sortable()
                    ->description(function($record){
                        return BusinessType::where('id', $record->business_type_id)->first()->name ?? "";
                    }),
                Tables\Columns\TextColumn::make('business_address_line_1')
                    ->label('Address/Contact')
                    ->description(function($record){
                        return "260".$record->business_phone_number;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_bank_name')
                    ->label('Bank Name')
                    ->searchable()
                    ->description(function($record){
                        return $record->business_bank_account_number;
                    }),
                Tables\Columns\TextColumn::make('business_bank_account_number')
                    ->label('Bank Acc No.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_bank_account_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_bank_account_branch_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_bank_account_branch_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_tpin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_reg_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('callback_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_checkout')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status'),
                Tables\Columns\IconColumn::make('is_deleted')
                    ->boolean()
                    ->visible(function(){
                        return auth()->user()->role_id == 1;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),

            ])
            ->recordUrl(
                fn (Business $record): string => url('businesses'),
            )
            ->filters([
                SelectFilter::make('is_active')
                ->multiple()
                ->options([
                    '0' => 'Pending',
                    '1' => 'Success',
                    '2' => 'De-activate'
                ]),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->visible(function (){
                            return checkReadBusinessesPermission();
                        }),
                    Tables\Actions\EditAction::make()
                        ->color('hyper')
                        ->visible(function (){
                        return checkUpdateBusinessesPermission();
                    }),
                    Tables\Actions\Action::make('Approve')
                        ->action(function (Business $record){
                            //change is_active column for Business and related Client accounts
                            $deactivate_business = Business::where('id', $record->id)->update([
                                "is_active" => 1
                            ]);

                            $deactivate_business_users = Client::where("business_id", $record->id)->update([
                                "is_active" => 1
                            ]);

                            //log user activity
                            $activity = AuditTrail::create([
                                "user_id" => Auth::user()->id,
                                "module" => "Businesses",
                                "activity" => "Activated Business record with ID ".$record->id,
                                "ip_address" => request()->ip()
                            ]);

                            $activity->save();

                            $account_owner_name = Client::where('id', $record->user_id)->first()->name;
                            //production
                            $api_secret_id_to_send =  apiAccessSecretId();
                            $api_access_token_to_send = apiAccessToken();
                            $password = "New.1234";

                            //update api credentials records
                            $production_api_credentials = APICredential::where("business_id", $record->id)->update([
                                "secret_id" => $api_secret_id_to_send,
                                "access_token" => Hash::make($api_access_token_to_send)
                            ]);

                            //send notification
                            SendAccountMail::dispatch($record->account_number,$account_owner_name,$api_secret_id_to_send,$api_access_token_to_send,$password,$record->business_email);
                        })
                        ->color('success')
                        ->icon('heroicon-m-hand-thumb-up')
                        ->requiresConfirmation()
                        ->modalHeading('Approve Business')
                        ->modalDescription(function($record){
                            return 'Are you sure you would like to approve this '.$record->business_name.' business account';
                        })
                        ->modalSubmitActionLabel('Yes, Approve')
                        ->visible(function(Business $record){
                            return checkUpdateBusinessesPermission() && $record->is_active == 0 && $record->is_delete == 0 && $record->user_id !== Auth::user()->id;
                        }),
                    Tables\Actions\Action::make('Deactivate')
                        ->action(function (Business $record){
                            //change is_active column for Business and related Client accounts
                            $deactivate_business = Business::where('id', $record->id)->update([
                                "is_active" => 0
                            ]);

                            $deactivate_business_users = Client::where("business_id", $record->id)->update([
                                "is_active" => 0
                            ]);

                            //log user activity
                            $activity = AuditTrail::create([
                                "user_id" => Auth::user()->id,
                                "module" => "Businesses",
                                "activity" => "Deactivated Business record with ID ".$record,
                                "ip_address" => request()->ip()
                            ]);

                            $activity->save();

                            $message_subject = "Account Deactivation";
                            $account_owner_name = Client::where('id', $record->user_id)->first()->name;
                            $message_to_send = "Your ".$record->business_name." account number ".$record->account_number." has been de-activated. Kindly call our Support for immediate action/resolution.";
                            //send notification
                            NotificationsMail::dispatch($record->account_number, $account_owner_name, $message_to_send, $message_subject, $record->business_email);
                        })
                        ->color('danger')
                        ->icon('heroicon-m-hand-thumb-down')
                        ->requiresConfirmation()
                        ->modalHeading('Deactivate Business')
                        ->modalDescription(function($record){
                            return 'Are you sure you want to deactivate '. $record->business_name." business account?";
                        })
                        ->modalSubmitActionLabel('Yes, Deactivate')
                        ->visible(function(Business $record){
                            return checkUpdateBusinessesPermission() && $record->is_active == 1 && $record->is_delete == 0;
                        }),
                    Tables\Actions\Action::make('Delete')
                        ->action(function($record){
                            //change is_active column for Business and related Client accounts
                            $delete_business = Business::where('id', $record->id)->update([
                                "is_active" => 0,
                                "is_deleted" => 1
                            ]);

                            $delete_business_users = Client::where("business_id", $record->id)->update([
                                "is_active" => 0,
                                "is_deleted" => 1
                            ]);

                            //log user activity
                            $activity = AuditTrail::create([
                                "user_id" => Auth::user()->id,
                                "module" => "Businesses",
                                "activity" => "Deleted Business record with details ".$record,
                                "ip_address" => request()->ip()
                            ]);

                            $activity->save();

                            $message_subject = "Account Deleted";
                            $account_owner_name = Client::where('id', $record->user_id)->first()->name;
                            $message_to_send = "Your ".$record->business_name." account number ".$record->account_number." has been deleted";
                            //send notification
                            NotificationsMail::dispatch($record->account_number, $account_owner_name, $message_to_send, $message_subject, $record->business_email);
                        })
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Business')
                        ->modalDescription(function($record){
                            return "This delete action is permanent and cannot be undone";
                        })
                        ->modalSubmitActionLabel('Yes, Delete')
                        ->visible(function(Business $record){
                            return checkDeleteBusinessesPermission() && $record->is_delete == 0;
                        }),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->visible(function (){
                        return checkCreateBusinessesPermission();
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
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
