<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupAppointment extends Model
{
    protected $table = 'group_appointments';
    protected $fillable = [
        'title',
        'business_id',
        'description',
        'date',
        'start_time',
        'end_time',
        'duration',
        'price',
        'service_category_id',
        'status',
        'reminders',
        'max_capacity',
        'count_ppl'
    ];

    public function service_category():BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
    public function group_appointment_has_customers(){
        return $this->hasMany(GroupAppointmentHasCustomers::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

}
