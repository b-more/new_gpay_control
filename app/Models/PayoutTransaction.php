<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'payout_id',
        'amount',
        'transaction_charge',
        'amount_payable',
        'internal_reference_number',
        'bank_reference_number',
        'status', //Initiated, Authorised, Success, Cancelled, Failed
        'transaction_method', //manual or auto
        'remarks',
        'initiated_by',
        'authorised_by',
        'confirmed_by',
        'old_balance',
        'new_balance',
        'business_bank_account_number',
        'business_bank_account_name',
        'business_bank_account_branch_name',
        'business_bank_account_branch_code',
        'business_bank_account_sort_code',
        'business_bank_account_swift_code',
        'initiated_at',
        'authorised_at',
        'confirmed_at',
    ];
}
