<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerCurrentBalanceLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "amount",
        "is_active",
        "is_deleted"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
