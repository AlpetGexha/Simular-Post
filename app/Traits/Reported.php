<?php

namespace App\Traits;

trait Reported
{
    public function reports()
    {
        return $this->morphMany(\App\Models\Report::class, 'reportable');
    }

    public function reportCount()
    {
        return $this->reports()->count();
    }
}
