<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'sold_at',
        'sell_price',
        'contact_id',
        'car_id',
        'insurance_type',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
