<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Agent extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        "name",
        "email",
        "phone_number",
        "image",
        "password",
        "nrc_number",
        "district_id",
        "province_id",
        "is_active"
    ];

    public function consumer()
    {
        return $this->hasMany(Consumer::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
