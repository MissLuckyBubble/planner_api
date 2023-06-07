<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAppointmentHasCustomers extends Model{
    protected $fillable = [
        'group_appointment_id',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function group_appointment()
    {
        return $this->belongsTo(Customer::class);
    }
}
