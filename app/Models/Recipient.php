<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'created_by',
        'category',
        'name',
        'phone_number',
        'account_number',
        'is_active'
    ];
}
