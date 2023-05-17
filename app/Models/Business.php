<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Business extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'eik',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
        'addressId',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function businessHasCategories()
    {
        return $this->hasMany(BusinessHasCategory::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function workDays()
    {
        return $this->hasMany(WorkDay::class);
    }

    public function custom_days_off()
    {
        return $this->hasMany(CustomDayOff::class);
    }

    public function service_categories(){
        return $this->hasMany(ServiceCategory::class);
    }
    public function services(){
        return $this->hasMany(Service::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings->avg('rate');
    }
}
