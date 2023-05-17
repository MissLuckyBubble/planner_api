<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentNoCustomer extends Model
{
    protected $fillable = [
        'business_id',
        'date',
        'total_price',
        'start_time',
        'end_time',
        'duration',
        'status',
        'name',
        'phoneNumber',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services', 'appointment_no_customers_id', 'service_id');
    }


    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
