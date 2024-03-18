<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'customer_id',
        'account_number',
        'business_name',
        'payment_channel',
        'payment_reference_number',
        'refund_reference_number',
        'phone_number',
        'reason',
        'refunded_amount',
        'commission_charged',
        'total_amount', // refunded_amount + commission_charged
        'status' //2 for success
    ];
}
