<?php

namespace App\Models;

use App\Enums\ContractType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getNameAttribute()
    {
        if (! $this->carModel) {
            return '';
        }

        $out = $this->brand->name.' '.$this->carModel->name;

        return $out;
    }

    public function getNameWithYearAttribute()
    {
        return $this->name.' ('.$this->year.')';
    }

    public function getYearAttribute()
    {
        return Carbon::parse($this->initial_date)->format('Y');
    }

    public function getKilometersFormattedAttribute()
    {
        return number_format($this->kilometers, 0, '.', '\'');
    }

    public function getInitialDateFormattedAttribute()
    {
        return Carbon::parse($this->initial_date)->format('d.m.Y');
    }

    public function getLastCheckDateFormattedAttribute()
    {
        if ($this->last_check_date) {
            return Carbon::parse($this->last_check_date)->format('d.m.Y');
        }

        return null;
    }

    public function getDeletedAtAttribute($deleted_at)
    {
        if ($deleted_at) {
            return Carbon::parse($deleted_at)->format('d.m.Y');
        }

        return null;
    }

    public function getLinkAttribute()
    {
        return route('cars.show', $this);
    }

    public function brand()
    {
        return $this->carModel->brand();
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function latestSellContract()
    {
        return $this->hasOne(Contract::class)->sellContracts()->latest('date')->first();
    }

    public function latestBuyContract()
    {
        return $this->hasOne(Contract::class)->buyContracts()->latest('date')->first();
    }

    public function latestSellContractUpToYear($year)
    {
        return $this->hasOne(Contract::class)->sellContracts()->whereYear('date', '<=', $year)->latest('date')->first();
    }

    public function latestBuyContractUpToYear($year)
    {
        return $this->hasOne(Contract::class)->buyContracts()->whereYear('date', '<=', $year)->latest('date')->first();
    }

    public function isSold()
    {
        return $this->buyContracts()->count() <= $this->sellContracts()->count() && $this->sellContracts()->count() > 0;
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
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
                $query->where('type', (string) ContractType::BuyContract)
                ->whereNull('deleted_at');
            },
            'contracts AS sell_contracts_count' => function ($query) {
                $query->where('type', (string) ContractType::SellContract)
                ->whereNull('deleted_at');
            },
        ]);
    }

    public function scopeWithContractCountUpToYear($query, $year)
    {
        return $query->withCount([
            'contracts AS buy_contracts_count' => function ($query) use ($year) {
                $query->where('type', (string) ContractType::BuyContract)
                ->whereYear('date', '<=', $year)
                ->whereNull('deleted_at');
            },
            'contracts AS sell_contracts_count' => function ($query) use ($year) {
                $query->where('type', (string) ContractType::SellContract)
                ->whereYear('date', '<=', $year)
                ->whereNull('deleted_at');
            },
        ]);
    }

    public function scopeUnsoldOnly($query)
    {
        return $query->withContractCount()->havingRaw('buy_contracts_count > sell_contracts_count OR sell_contracts_count = 0');
    }

    public function scopeUnsoldOnlyByYear($query, $year)
    {
        return $query->withContractCountUpToYear($year)->havingRaw('buy_contracts_count > sell_contracts_count');
    }

    public function scopeSoldOnly($query)
    {
        return $query->withContractCount()->having('sell_contracts_count', '>', 0)->havingRaw('buy_contracts_count <= sell_contracts_count');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $parts = explode(' ', $search);
            foreach ($parts as $part) {
                $query->where(function ($query) use ($part) {
                    $query->orWhere('colour', 'like', $part.'%')
                        ->orWhere('stammnummer', 'like', $part.'%')
                        ->orWhere('vin', 'like', $part.'%')
                        ->orWhereHas('carModel', function ($query) use ($part) {
                            $query->where('name', 'like', $part.'%')
                            ->orWhereHas('brand', function ($query) use ($part) {
                                $query->where('name', 'like', $part.'%');
                            });
                        });
                });
            }
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['brand'] ?? null, function ($query, $brand) {
            $query->whereHas('carModel', function ($q) use ($brand) {
                $q->where('brand_id', '=', $brand);
            })->get();
        });
    }
}
