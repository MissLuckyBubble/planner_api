<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_off',
        'start_time',
        'end_time',
        'pause_start',
        'pause_end'
    ];
    protected $hidden = [
        'business_id',
        'weekday_id',
    ];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function weekday()
    {
        return $this->belongsTo(Weekday::class);
    }
}
