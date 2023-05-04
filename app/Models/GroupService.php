<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupService extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'duration_minutes',
        'price',
        'service_category_id',
        'max_capacity',
    ];

    public function service_category():BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
