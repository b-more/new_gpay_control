<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoTvPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'voucher_type',
        'voucher_value',
        'voucher_id',
        'is_fixed',
        'is_active'
    ];
}
