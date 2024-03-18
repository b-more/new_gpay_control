<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        "business_id",
        "account_number",
        "business_name",
        "old_balance",
        "new_balance"
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function payout()
    {
        return $this->hasMany(PayoutTransaction::class);
    }
}
