<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'description',
        'icon'
    ];

    protected $hidden = [

    ];

    public function businessHasCategories()
    {
        return $this->hasMany(BusinessHasCategory::class);
    }
}
