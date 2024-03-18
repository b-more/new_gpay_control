<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'customer_id',
        'payment_method_id',
        'account_number',
        'business_name',
        'payment_channel',
        'payment_reference_number',
        'txn_number',
        'phone_number',
        'description',
        'received_amount',
        'commission_charged',
        'total_amount',
        'status', //0 or 1 or 2
        'is_deleted',
        'is_refunded',
        'short_url'
    ];
}
