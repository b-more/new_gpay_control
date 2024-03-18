<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'module',
        'create',
        'read',
        'update',
        'delete',
        'initiator',
        'authorizer'
    ];

    protected $casts = [
        'create' => 'boolean',
        'read' => 'boolean',
        'update' => 'boolean',
        'delete' => 'boolean',
        'initiator' => 'boolean',
        'authorizer' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
