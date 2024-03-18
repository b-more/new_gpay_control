<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_id',
        'business_id',
        'amount',
        'deposit_method',
        'reference_number',
        'deposit_mode',
        'status',
        'remarks',
        'deposit_slip',
        'recorded_by',
        'authorised_by',
        'old_balance',
        'new_balance'
    ];

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}
