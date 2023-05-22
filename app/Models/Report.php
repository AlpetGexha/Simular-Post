<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reportable()
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::created(function (Report $report) {
            if ($report->reportable_type === Post::class) {
                Post::where('id', $report->reportable_id)->increment('reports_count');
            }
        });
    }
}
