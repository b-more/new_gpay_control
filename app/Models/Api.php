<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'invoice_no',
        'quantity',
        'unit_price',
        'total_price',
        'tax',
        'total_paid',
        'client_id'
    ];

}
