<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date',
        'type',
        'sell_contract_id',
    ];

    public function sellContract()
    {
        return $this->belongsTo(SellContract::class);
    }

    public function car()
    {
        return $this->hasOneThrough(Car::class, SellContract::class);
    }
}
