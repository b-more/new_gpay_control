<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'description',
        'amount',
        'visits',
        'short_url',
        'is_deleted'
    ];
}
