<?php

namespace App\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use App\Enums\ContractType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'date',
        'price',
        'contact_id',
        'car_id',
        'type',
        'insurance_type',
    ];

    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }

    public function getPriceAttribute($price)
    {
        return Money::CHF($price);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function scopeThisYear($query)
    {
        $query->where('date', '>', date('Y'));
    }

    public function scopeSoldThisYear($query)
    {
        $query->buyContracts()->thisYear();
    }

    public function scopeBoughtThisYear($query)
    {
        $query->sellContracts()->thisYear();
    }

    public function scopeBuyContracts($query)
    {
        $query->where('type', (string)ContractType::BuyContract);
    }

    public function scopeSellContracts($query)
    {
        $query->where('type', (string)ContractType::SellContract);
    }
}
