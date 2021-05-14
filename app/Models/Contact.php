<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'address',
        'zip',
        'city',
        'country',
        'company',
        'email',
        'notes',
    ];

    public function getNameAttribute()
    {
        return $this->lastname . ' ' . $this->firstname;
    }

    public function getTitleAttribute()
    {
        if ($this->company != '') {
            return $this->company;
        }

        return $this->name;
    }

    public function getFullCityAttribute()
    {
        return $this->zip . ' ' . $this->city;
    }

    public function scopeOrderByName($query, $direction)
    {
        $query->orderBy('lastname', $direction)->orderBy('firstname', $direction);
    }

    public function sellContracts()
    {
        return $this->hasMany(SellContract::class);
    }

    public function buyContracts()
    {
        return $this->hasMany(BuyContract::class);
    }

    public function boughtCars()
    {
        return $this->hasManyThrough(Car::class, SellContract::class);
    }

    public function soldCars()
    {
        return $this->hasManyThrough(Car::class, BuyContract::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('company', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('zip', 'like',  $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
