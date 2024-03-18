<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'account_number',
        'business_name',
        'old_balance',
        'new_balance',
        'comment',
        'recorded_by',
        'authorised_by',
        'is_deleted'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function DepositTransaction()
    {
        return $this->hasMany(DepositTransaction::class);
    }
}
