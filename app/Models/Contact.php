<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'street',
        'zip',
        'city',
        'country',
        'company',
        'email',
        'notes',
    ];

    public function contracts()
    {
        return $this->hasMany(Contracts::class);
    }

    public function boughtCars()
    {
        return $this->hasManyThrough(Car::class, Contracts::class);
    }

    public function soldCars()
    {
        return $this->hasMany(Car::class, 'seller_id');
    }
}
