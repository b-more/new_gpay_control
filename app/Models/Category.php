<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "business_id",
        "group_id",
        "name",
        "is_active",
        "is_deleted"
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
