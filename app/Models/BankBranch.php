<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name_id',
        'branch_name',
        'branch_code',
        'closure_date',
        'status'
    ];
    public function bankName()
    {
        return $this->belongsTo(BankName::class);
    }
}
