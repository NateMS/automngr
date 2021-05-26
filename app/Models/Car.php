<?php

namespace App\Models;

use App\Enums\ContractType;
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

    public function getLatestBuyContractAttribute()
    {
        return $this->buyContracts()->latest('date')->first();
    }

    public function getLatestSellContractAttribute()
    {
        return $this->sellContracts()->latest('date')->first();
    }

    public function isUnsold()
    {
        return $this->buyContracts()->count() > $this->sellContracts()->count();
    }

    public function isSold()
    {
        return $this->buyContracts()->count() == $this->sellContracts()->count();
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    // public function scopeSoldThisYear($query)
    // {
    //     return $query->whereDate('sold_at', '>=', Carbon::today()->format('Y'));
    // }

    public function scopeOrderByInitialDate($query)
    {
        $query->orderBy('initial_date');
    }

    public function buyContracts()
    {
        return $this->hasMany(Contract::class)->buyContracts();
    }

    public function sellContracts()
    {
        return $this->hasMany(Contract::class)->sellContracts();
    }

    public function scopeWithContractCount($query)
    {
        return $query->withCount([
            'contracts AS buy_contracts_count' => function ($query) {
                $query->where('type', (string)ContractType::BuyContract);
            },
            'contracts AS sell_contracts_count' => function ($query) {
                $query->where('type', (string)ContractType::SellContract);
            },
        ]);
    }

    public function scopeUnsoldOnly($query)
    {
        return $query->withContractCount()->havingRaw('buy_contracts_count > sell_contracts_count');
    }

    public function scopeSoldOnly($query)
    {
        return $query->withContractCount()->having('sell_contracts_count', '>', 0)->havingRaw('buy_contracts_count <= sell_contracts_count');
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
