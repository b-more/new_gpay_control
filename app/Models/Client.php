<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasApiTokens,  Notifiable;

    protected $fillable = [
        'role_id',
        'business_id',
        'name',
        'phone_number',
        'email',
        'avatar',
        'password',
        'is_email_verified',
        'is_active',
        'is_deleted',
        'is_account_owner',
        'verify_token'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'user_id');
    }
}
