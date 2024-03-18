<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Consumer extends Model
{
    use HasFactory, HasApiTokens;



    protected $fillable = [
        'agent_id',
        'name',
        'avatar',
        'nrc_front',
        'nrc_back',
        'selfie',
        'email',
        'phone_number',
        'nrc_number',
        'qr_code',
        'password',
        'is_active',
        'is_deleted',
        'otp',
        'gender',
        'dob'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
