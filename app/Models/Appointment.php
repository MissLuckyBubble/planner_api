<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id',
        'business_id',
        'date',
        'total_price',
        'start_time',
        'end_time',
        'duration',
        'status',
        'reminders'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services', 'appointment_id', 'service_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

}
