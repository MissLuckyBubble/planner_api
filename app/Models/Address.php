<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'city',
        'street',
        'number',
        'floor',
        'description',
        'latitude',
        'longitude'
    ];

    protected $hidden = [

    ];

    public function business()
    {
        return $this->hasOne(Business::class);
    }
}
