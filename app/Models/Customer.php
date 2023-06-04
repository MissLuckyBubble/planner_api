<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'birth_day',
        'sex'
    ];

    protected $hidden = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function customer_has_favorite_busineses(){
        return $this->hasMany(Customer_Has_Favorite_Business::class);
    }

    public function group_appointment_has_customers(){
        return $this->hasMany(GroupAppointmentHasCustomers::class);
    }
}
