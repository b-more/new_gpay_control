<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'is_active',
        'is_deleted',
        'session_id'
    ];

    public function users(){
        return $this->belongsTo(related:User::class);
    }
}
