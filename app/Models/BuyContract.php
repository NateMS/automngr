<?php

namespace App\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BuyContract extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'date',
        'price',
        'contact_id',
        'car_id',
    ];

    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }

    public function getPriceAttribute($price)
    {
        return Money::CHF($price)->format();
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
