<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerTransaction extends Model
{
    use HasFactory;

    protected $fillable =[
        'consumer_id',
        'consumer_name',
        'payment_reference_number',
        'type', //send,deposit,transfer,pay
        'partner',//phone number, business_id, Agent_id, third-party name
        'partner_type',//Wallet, Business,Agent,third-party name
        'amount',
        'status',
        'channel',
        'method',
        'purpose',
        'custom_message',
        'lead',
        'phone_number',
        'meta_data'
    ];
}
