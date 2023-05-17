<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $fillable = [
        'customer_id',
        'business_id',
        'appointment_id',
        'rate',
        'comment',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
