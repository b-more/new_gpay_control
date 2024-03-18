<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthActivityTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'browser',
        'screen_time',
        'device_type',
        'activity_initiator',
        'session_id',
        'latitude',
        'longitude',
        'city',
        'country'
    ];
}
