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

    public function scopeOrderByName($query)
    {
        $query->orderBy('lastname')->orderBy('firstname');
    }

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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
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
