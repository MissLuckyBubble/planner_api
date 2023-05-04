<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'duration_minutes',
        'service_category_id',
    ];

    public function service_category():BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
