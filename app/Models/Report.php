<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "report_type_id",
        "start_date",
        "end_date",
        "query"
    ];

    protected $casts = [
        "start_date" => "datetime",
        "end_date" => "datetime"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report_type()
    {
        return $this->belongsTo(ReportType::class);
    }
}
