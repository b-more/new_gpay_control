<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccumulativeBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'disbursement',
        'payments',
        'payouts',
        'is_deleted',
        'is_active'
    ];
}
