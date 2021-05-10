<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'paid_at',
        'payment_type',
        'contract_id'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function car()
    {
        return $this->hasOneThrough(Car::class, Contract::class);
    }
}
