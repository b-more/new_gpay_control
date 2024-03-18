<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APICredential extends Model
{
    use HasFactory;

    protected $fillable =[
        'secret_id',
        'access_token',
        'business_id',
        'environment',
        'reset_count'
    ];
}
