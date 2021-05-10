<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'variation',
        'stammnummer',
        'vin',
        'colour',
        'notes',
        'known_damage',
        'initial_date',
        'bought_at',
        'buy_price',
        'seller_contact_id',
        'car_model_id'
    ];

    public function getNameAttribute()
    {
        return $this->brand->name . ' ' . $this->carModel->car_model . $this->variation ? '('  . $this->variation . ')' : '';
    }

    public function brand()
    {
        return $this->hasOneThrough(Brand::class, CarModel::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function seller()
    {
        return $this->belongsTo(Contact::class, 'seller_contact_id');
    }

    public function buyer()
    {
        return $this->hasOneThrough(Contract::class, Contact::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function carPayment()
    {
        return $this->hasManyThrough(CarPayment::class, Contract::class);
    }
}
