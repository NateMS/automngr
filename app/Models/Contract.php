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
        'delivery_date',
        'price',
        'contact_id',
        'car_id',
        'type',
        'notes',
        'insurance_type',
    ];

    public function getDateFormattedAttribute()
    {
        return Carbon::parse($this->date)->format('d.m.Y');
    }

    public function getDeliveryDateFormattedAttribute()
    {
        return Carbon::parse($this->delivery_date)->format('d.m.Y');
    }

    public function getPriceAttribute($price)
    {
        return Money::CHF($price);
    }

    public function getPriceForExcelAttribute()
    {
        return $this->price->format(null, null, 0);
    }

    public function getPaidAttribute()
    {
        
        return Money::CHF($this->payments()->sum('amount'));
    }

    public function getPaidInCashAttribute()
    {
        
        return Money::CHF($this->payments()->cashOnly()->sum('amount'));
    }

    public function getPaidInTransactionAttribute()
    {
        
        return Money::CHF($this->payments()->transactionOnly()->sum('amount'));
    }

    public function getPaidInCembraAttribute()
    {
        
        return Money::CHF($this->payments()->cembraOnly()->sum('amount'));
    }

    public function getLeftToPayAttribute()
    {
        
        return $this->price->subtract($this->paid);
    }

    public function getInsuranceTypeFormattedAttribute()
    {
        return match ($this->insurance_type) {
            InsuranceType::QBase => 'Q Basis',
            InsuranceType::OneStar => '1 Stern',
            InsuranceType::ThreeStar => '3 Stern',
            InsuranceType::FiveStar => '5 Stern',
            InsuranceType::FiveStarPlus => '5 Stern+',
            default => 'Nein',
        };
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
        return $this->isSellContract() ? 'Kaufvertrag' : 'Ankaufsvertrag';
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deposit()
    {
        return $this->payments()->oldest('date')->first();
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
        $query->whereYear('date', date('Y'));
    }

    public function scopeSoldThisYear($query)
    {
        $query->sellContracts()->thisYear();
    }

    public function scopeSoldByYear($query, $year)
    {
        $query->sellContracts()->whereYear('date', $year);
    }

    public function scopeBoughtThisYear($query)
    {
        $query->buyContracts()->thisYear();
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
