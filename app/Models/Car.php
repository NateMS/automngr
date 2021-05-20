<?php

namespace App\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'stammnummer',
        'vin',
        'colour',
        'notes',
        'known_damage',
        'initial_date',
        'last_check_date',
        'kilometers',
        'car_model_id',
    ];

    public function getNameAttribute()
    {
        $out = $this->brand->name . ' ' . $this->carModel->name;

        return $out;
    }

    public function getKilometersAttrobute($kilometers)
    {
        return $kilometers;
    }

    // public function getBuyPriceAttribute()
    // {
    //     return Money::CHF($this->buyContracts()->price);
    // }

    // public function getInitialDateAttribute($initialDate)
    // {
    //     return Carbon::parse($initialDate)->format('d.m.Y');
    // }

    public function brand()
    {
        return $this->carModel->brand();
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }
    
    public function latestSellerContract()
    {
        return $this->buyContracts()->latest('date')->first();
    }

    public function latestBuyerContracts()
    {
        return $this->sellContracts()->latest('date')->first();
    }

    public function isUnsold()
    {
        return $this->sellers()->count() > $this->buyers()->count();
    }

    public function isSold()
    {
        return $this->sellers()->count() == $this->buyers()->count();
    }

    public function sellers()
    {
        return $this->hasManyThrough(BuyContract::class, Contact::class);
    }

    public function buyers()
    {
        return $this->hasManyThrough(SellContract::class, Contact::class);
    }

    public function buyContracts()
    {
        return $this->hasMany(BuyContract::class);
    }

    public function sellContracts()
    {
        return $this->hasMany(SellContract::class);
    }

    public function carPayment()
    {
        return $this->hasManyThrough(CarPayment::class, SellContract::class);
    }

    // public function scopeSoldThisYear($query)
    // {
    //     return $query->whereDate('sold_at', '>=', Carbon::today()->format('Y'));
    // }

    public function scopeOrderByInitialDate($query)
    {
        $query->orderBy('initial_date');
    }

    public function scopeSoldCars($query)
    {
        $query->withCount(['buyContracts', 'sellContracts'])->having('buy_contracts_count', '=', 'sell_contracts_count');
    }

    public function scopeUnsoldCars($query)
    {
        $query->withCount(['buyContracts', 'sellContracts'])->having('buy_contracts_count', '>', 'sell_contracts_count');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('colour', 'like', $search . '%')
                    ->orWhere('stammnummer', 'like', $search . '%')
                    ->orWhere('vin', 'like', $search . '%')
                    ->orWhereHas('carModel', function ($query) use ($search) {
                        $query->where('name', 'like', $search.'%')
                        ->orWhereHas('brand', function ($query) use ($search) {
                            $query->where('name', 'like', $search.'%');
                        });
                    });
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
