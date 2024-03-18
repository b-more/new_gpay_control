<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityRedFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'reason',
        'level',
        'session_id'
    ];
}
