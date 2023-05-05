<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_Has_Favorite_Business extends Model
{
    protected $fillable = [
        'business_id',
        'customer_id',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function customer()
    {
        return $this->belongsTo(Business::class);
    }
}
