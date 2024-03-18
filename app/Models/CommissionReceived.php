<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionReceived extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'payment_reference_number',
        'amount'
    ];
}
