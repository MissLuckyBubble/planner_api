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
        'business_id'
    ];

    public function service_category():BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
    public function business():BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services', 'service_id', 'appointment_id');
    }
    public function appointmentsNoCustomer()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services', 'service_id', 'appointment_no_customers_id');
    }
}
