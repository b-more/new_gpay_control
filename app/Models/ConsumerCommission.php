<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        "consumer_id",
        "transaction_reference_number",
        "cgrate_percentage",
        "geepay_percentage",
        "cgrate_fixed_charge",
        "geepay_fixed_charge"
    ];
}
