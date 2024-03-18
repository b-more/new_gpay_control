<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\AuditTrail;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

//AccumulativeBalance Model
function checkCreateAccumulativeBalancePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Accumulative Balances')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadAccumulativeBalancePermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Accumulative Balances')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateAccumulativeBalancePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Accumulative Balances')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteAccumulativeBalancePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Accumulative Balances')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Agents Model
function checkCreateAgentsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Agents')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadAgentsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Agents')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateAgentsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Agents')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteAgentsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Agents')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Bank Branches Model
function checkCreateBankBranchesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Bank Branches')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadBankBranchesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Bank Branches')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateBankBranchesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Bank Branches')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteBankBranchesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Bank Branches')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Bank Names Model
function checkCreateBankNamesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Bank Names')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadBankNamesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Bank Names')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateBankNamesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Bank Names')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteBankNamesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Bank Names')->where('role_id', $user->role_id)->first()->delete == 1;
}



//APICredentials Model
function checkCreateAPICredentialPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'API Credentials')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadAPICredentialPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'API Credentials')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateAPICredentialPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'API Credentials')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteAPICredentialPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'API Credentials')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Audit Trails Model
function checkCreateAuditTrailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Audit Trails')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadAuditTrailsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Audit Trails')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateAuditTrailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Audit Trails')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteAuditTrailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Audit Trails')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Audit Trails Model
function checkCreateAuthActivityTrailPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Auth Activity Trails')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadAuthActivityTrailPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Auth Activity Trails')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateAuthActivityTrailPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Auth Activity Trails')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteAuthActivityTrailPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Auth Activity Trails')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Business Category Model
function checkCreateBusinessCategoryPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Business Category')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadBusinessCategoryPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Business Category')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateBusinessCategoryPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Business Category')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteBusinessCategoryPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Business Category')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Businesses Model
function checkCreateBusinessesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Businesses')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadBusinessesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Businesses')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateBusinessesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Businesses')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteBusinessesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Businesses')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Business Types Model
function checkCreateBusinessTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Business Types')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadBusinessTypesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Business Types')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateBusinessTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Business Types')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteBusinessTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Business Types')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Client Audit Trails Model
function checkCreateClientAuditTrailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Client Audit Trails')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadClientAuditTrailsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Client Audit Trails')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateClientAuditTrailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Client Audit Trails')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteClientAuditTrailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Client Audit Trails')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Clients Model
function checkCreateClientsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Clients')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadClientsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Clients')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateClientsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Clients')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteClientsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Clients')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Commission Received Model
function checkCreateCommissionReceivedPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Commission Received')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadCommissionReceivedPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Commission Received')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateCommissionReceivedPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Commission Received')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteCommissionReceivedPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Commission Received')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Commissions Model
function checkCreateCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Commissions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadCommissionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Commissions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Commissions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Commissions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumer Balances Model
function checkCreateConsumerBalancesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Balances')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumerBalancesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumer Balances')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumerBalancesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Balances')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumerBalancesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Balances')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumer Commissions Model
function checkCreateConsumerCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Commissions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumerCommissionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumer Commissions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumerCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Commissions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumerCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Commissions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumer Commission Structure Model
function checkCreateConsumerCommissionStructurePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Commission Structure')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumerCommissionStructurePermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumer Commission Structure')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumerCommissionStructurePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Commission Structure')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumerCommissionStructurePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Commission Structure')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumer Current Balance Limits Model
function checkCreateConsumerCurrentBalanceLimitsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Current Balance Limits')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumerCurrentBalanceLimitsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumer Current Balance Limits')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumerCurrentBalanceLimitsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Current Balance Limits')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumerCurrentBalanceLimitsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Current Balance Limits')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumer Daily Withdraw Limits Model
function checkCreateConsumerDailyWithdrawLimitsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Daily Withdraw Limits')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumerDailyWithdrawLimitsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumer Daily Withdraw Limits')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumerDailyWithdrawLimitsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Daily Withdraw Limits')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumerDailyWithdrawLimitsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Daily Withdraw Limits')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumers Model
function checkCreateConsumersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumers')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumersPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumers')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumers')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumers')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Consumer Transactions Model
function checkCreateConsumerTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Transactions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadConsumerTransactionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Consumer Transactions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateConsumerTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Transactions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteConsumerTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Consumer Transactions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Countries Transactions Model
function checkCreateCountriesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Countries')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadCountriesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Countries')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateCountriesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Countries')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteCountriesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Countries')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Current Balance Transactions Model
function checkCreateCurrentBalancePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Current Balance')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadCurrentBalancePermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Current Balance')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateCurrentBalancePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Current Balance')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteCurrentBalancePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Current Balance')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Customers Model
function checkCreateCustomersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Customers')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadCustomersPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Customers')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateCustomersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Customers')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteCustomersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Customers')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Deposits Model
function checkCreateDepositsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Deposits')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadDepositsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Deposits')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateDepositsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Deposits')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteDepositsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Deposits')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Deposit Transactions Model
function checkCreateDepositTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Deposit Transactions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadDepositTransactionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Deposit Transactions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateDepositTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Deposit Transactions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteDepositTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Deposit Transactions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Disputes Model
function checkCreateDisputesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Disputes')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadDisputesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Disputes')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateDisputesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Disputes')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteDisputesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Disputes')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Districts Model
function checkCreateDistrictsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Districts')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadDistrictsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Districts')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateDistrictsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Districts')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteDistrictsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Districts')->where('role_id', $user->role_id)->first()->delete == 1;
}

//DSTV Packages Model
function checkCreateDSTVPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'DSTV Packages')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadDSTVPackagesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'DSTV Packages')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateDSTVPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'DSTV Packages')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteDSTVPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'DSTV Packages')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Front-end URLs Model
function checkCreateFrontendURLsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Front-end URLs')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadFrontendURLsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Front-end URLs')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateFrontendURLsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Front-end URLs')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteFrontendURLsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Front-end URLs')->where('role_id', $user->role_id)->first()->delete == 1;
}

//GoTV Packages Model
function checkCreateGoTVPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'GoTV Packages')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadGoTVPackagesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'GoTV Packages')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateGoTVPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'GoTV Packages')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteGoTVPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'GoTV Packages')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Liquid Telecom Packages Model
function checkCreateLiquidTelecomPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Liquid Telecom Packages')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadLiquidTelecomPackagesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Liquid Telecom Packages')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateLiquidTelecomPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Liquid Telecom Packages')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteLiquidTelecomPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Liquid Telecom Packages')->where('role_id', $user->role_id)->first()->delete == 1;
}

//NRC Details Model
function checkCreateNRCDetailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'NRC Details')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadNRCDetailsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'NRC Details')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateNRCDetailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'NRC Details')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteNRCDetailsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'NRC Details')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payment Links Model
function checkCreatePaymentLinksPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payment Links')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPaymentLinksPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payment Links')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePaymentLinksPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payment Links')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePaymentLinksPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payment Links')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payments Model
function checkCreatePaymentsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payments')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPaymentsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payments')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePaymentsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payments')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePaymentsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payments')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payment Commission Model
function checkCreatePaymentCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payment Commissions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPaymentCommissionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payment Commissions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePaymentCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payment Commissions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePaymentCommissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payment Commissions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payouts Model
function checkCreatePayoutsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payouts')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payouts')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payouts')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payouts')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payout Transactions Model
function checkCreatePayoutTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Transactions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutTransactionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payout Transactions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Transactions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutTransactionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Transactions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Permissions Model
function checkCreatePermissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Permissions')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPermissionsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Permissions')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePermissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Permissions')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePermissionsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Permissions')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Provinces Model
function checkCreateProvincesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Provinces')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadProvincesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Provinces')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateProvincesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Provinces')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteProvincesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Provinces')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Refunds Model
function checkCreateRefundsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Refunds')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadRefundsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Refunds')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateRefundsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Refunds')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteRefundsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Refunds')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Reports Model
function checkCreateReportsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Reports')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadReportsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Reports')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateReportsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Reports')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteReportsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Reports')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Report Types Model
function checkCreateReportTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Report Types')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadReportTypesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Report Types')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateReportTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Report Types')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteReportTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Report Types')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Roles Types Model
function checkCreateRolesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Roles')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadRolesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Roles')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateRolesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Roles')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteRolesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Roles')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Security Red Flags Model
function checkCreateSecurityRedFlagsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Security Red Flags')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadSecurityRedFlagsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Security Red Flags')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateSecurityRedFlagsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Security Red Flags')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteSecurityRedFlagsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Security Red Flags')->where('role_id', $user->role_id)->first()->delete == 1;
}

//ShowMax Packages Model
function checkCreateShowMaxPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'ShowMax Packages')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadShowMaxPackagesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'ShowMax Packages')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateShowMaxPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'ShowMax Packages')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteShowMaxPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'ShowMax Packages')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Statuses Model
function checkCreateStatusesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Statuses')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadStatusesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Statuses')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateStatusesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Statuses')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteStatusesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Statuses')->where('role_id', $user->role_id)->first()->delete == 1;
}

//TopStarPackages Model
function checkCreateTopStarPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'TopStar Packages')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadTopStarPackagesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'TopStar Packages')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateTopStarPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'TopStar Packages')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteTopStarPackagesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'TopStar Packages')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Transfers Model
function checkCreateTransfersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Transfers')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadTransfersPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Transfers')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateTransfersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Transfers')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteTransfersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Transfers')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Two Factors Model
function checkCreateTwoFactorsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Two Factors')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadTwoFactorsPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Two Factors')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateTwoFactorsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Two Factors')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteTwoFactorsPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Two Factors')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Users Model
function checkCreateUsersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Users')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadUsersPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Users')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateUsersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Users')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteUsersPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Users')->where('role_id', $user->role_id)->first()->delete == 1;
}

//User Types Model
function checkCreateUserTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'User Types')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadUserTypesPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'User Types')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateUserTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'User Types')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteUserTypesPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'User Types')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Utility Bill Categories Model
function checkCreateUtilityBillCategoryPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Utility Bill Categories')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadUtilityBillCategoryPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Utility Bill Categories')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateUtilityBillCategoryPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Utility Bill Categories')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteUtilityBillCategoryPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Utility Bill Categories')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Utility Bills Model
function checkCreateUtilityBillPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Utility Bills')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadUtilityBillPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Utility Bills')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateUtilityBillPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Utility Bills')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteUtilityBillPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Utility Bills')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Webhook Model
function checkCreateWebhookPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Webhooks')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadWebhookPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Webhooks')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdateWebhookPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Webhooks')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeleteWebhookPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Webhooks')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payout Confirm Model
function checkCreatePayoutConfirmPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Confirm')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutConfirmPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payout Confirm')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutConfirmPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Confirm')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutConfirmPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Confirm')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payout Failed Model
function checkCreatePayoutFailedPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Failed')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutFailedPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payout Failed')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutFailedPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Failed')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutFailedPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Failed')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payout Authorise Model
function checkCreatePayoutAuthorisePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Authorise')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutAuthorisePermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payout Authorise')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutAuthorisePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Authorise')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutAuthorisePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Authorise')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payout Cancel Model
function checkCreatePayoutCancelPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Cancel')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutCancelPermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payout Cancel')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutCancelPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Cancel')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutCancelPermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Cancel')->where('role_id', $user->role_id)->first()->delete == 1;
}

//Payout Initiate Model
function checkCreatePayoutInitiatePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Initiate')->where('role_id', $user->role_id)->first()->create == 1;
}
function checkReadPayoutInitiatePermission(): bool
{
    $user = Auth::user();

    return Permission::where('module', 'Payout Initiate')->where('role_id', $user->role_id)->first()->read == 1;
}
function checkUpdatePayoutInitiatePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Initiate')->where('role_id', $user->role_id)->first()->update == 1;
}
function checkDeletePayoutInitiatePermission(): bool
{
    $user = Auth::user();
    return Permission::where('module', 'Payout Initiate')->where('role_id', $user->role_id)->first()->delete == 1;
}


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function shouldRegisterNavigation(): bool
    {
        return checkReadUsersPermission();
    }

    public static function authenticate()
    {
        Log::info("user logged in");
        $new_authentication = AuditTrail::create([
            "user_id" => Auth::user()->id,
            "module" => "Login",
            "activity" => "Logged into the account",
            "ip_address" => request()->ip()
        ]);

        $new_authentication->save();

    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('')
                                ->schema([

                                    FileUpload::make('avatar')
                                        ->label('Profile Picture')
                                        ->directory('profile_pics')
                                        ->avatar()
                                        ->required()
                                        ->imageEditor()
                                        ->default('/IAFOR-Blank-Avatar-Image.jpg')
                                        ->imageEditorAspectRatios([
                                            '5:4'
                                        ])
                                        ->columnSpan('full'),
                                ]),
                        ])
                        ->columnSpan(['lg' => 1]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('')
                                ->schema([
                                    Grid::make('')
                                        ->schema([
                                            TextInput::make('name')
                                                ->prefixIcon('heroicon-o-user')
                                                ->required(),
                                            TextInput::make('email')
                                                ->unique(ignoreRecord: true)
                                                ->email()
                                                ->prefixIcon('heroicon-o-user')
                                                ->required(),
                                        ])->columns(2),
                                    Grid::make('')
                                        ->schema([
                                            TextInput::make('phone_number')
                                                ->unique(ignoreRecord: true)
                                                ->length(10)
                                                ->prefix('+26')
                                                ->required(),
                                            TextInput::make('password')
                                                ->minLength(8)
                                                ->prefix('Password')
                                                ->password()
                                                ->maxLength(255)
                                                ->dehydrateStateUsing(static fn(null|string $state): null|string => filled($state) ? Hash::make($state) : null)
                                                ->required(static fn(Page $livewire): bool => $livewire instanceof Pages\CreateUser)
                                                ->dehydrated(static fn(null|string $state): bool => filled($state))
                                                ->label(static fn(Page $livewire):  string =>
                                                ($livewire instanceof EditUser) ? 'New Password' : 'password'
                                                ),
                                        ])
                                        ->columns(2),
                                    Grid::make('')
                                        ->schema([


                                            Select::make('role_id')
                                                ->options(Role::all()->pluck("name","id")->toArray())
                                                ->prefixIcon('heroicon-m-rectangle-stack')
                                                ->required(),
                                        ])
                                        ->columns(1),

                                ]),

                        ])
                        ->columnSpan(['lg' => 2]),
                ])
                ->columns(3)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\ImageColumn::make('avatar')->label('Profile Pic')->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->description(function (User $record){
                        return $record->email;
                    }),
                Tables\Columns\TextColumn::make('role.name')->label('Role')
                    ->sortable()
                    ->searchable()
                    ->description(function (User $record){
                        return $record->phone_number;
                    }),
                TextColumn::make('is_active')
                    ->badge()
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (){
                    return checkUpdateUsersPermission();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->visible(function (){
                        return checkCreateUsersPermission();
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
