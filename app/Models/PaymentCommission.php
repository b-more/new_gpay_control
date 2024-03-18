<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'cgrate_percentage',
        'geepay_percentage',
        'cgrate_fixed_charge',
        'geepay_fixed_charge',
        'is_active',
        'is_deleted'
    ];
}
