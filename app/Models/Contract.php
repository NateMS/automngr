<?php

namespace App\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use App\Enums\ContractType;
use App\Enums\InsuranceType;
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

    public function getDateFormattedAttribute()
    {
        return Carbon::parse($this->date)->format('d.m.Y');
    }

    public function getPriceAttribute($price)
    {
        return Money::CHF($price);
    }

    public function getInsuranceTypeFormattedAttribute()
    {
        switch ($this->insurance_type) {
            case InsuranceType::QBase: 
                return 'Q Basis';
            case InsuranceType::OneStar:
                return '1 Stern';
            case InsuranceType::ThreeStar:
                return '3 Stern';
            case InsuranceType::FiveStar:
                return '5 Stern';
            case InsuranceType::FiveStarPlus:
                return '5 Stern+';
            default:
                return 'Nein';
        }
    }

    public function getDeletedAtAttribute($deleted_at)
    {
        if ($deleted_at) {
            return Carbon::parse($deleted_at)->format('d.m.Y');
        }
        
        return null;
    }

    public function isBuyContract()
    {
        return $this->type === (string)ContractType::BuyContract;
    }

    public function isSellContract()
    {
        return $this->type === (string)ContractType::SellContract;
    }

    public function getTypeFormattedAttribute()
    {
        return $this->isSellContract() ? 'Kaufsvertrag' : 'Ankaufsvertrag';
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class)->withTrashed();
    }

    public function car()
    {
        return $this->belongsTo(Car::class)->withTrashed();
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
