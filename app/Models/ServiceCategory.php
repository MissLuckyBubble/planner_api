<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    protected $fillable = [
        'title',
        'business_id',
    ];

    public function business():BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function services():HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function group_appointments():HasMany
    {
        return $this->hasMany(GroupAppointment::class);
    }
}
