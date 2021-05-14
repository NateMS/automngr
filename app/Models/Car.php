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
        'bought_at',
        'buy_price',
        'seller_contact_id',
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

    public function getStammnummerAttribute($stammnummer)
    {
        $out = substr($stammnummer, 0, 3);
        $out .= '.';
        $out .= substr($stammnummer, 3, 3);
        $out .= '.';
        $out .= substr($stammnummer, 6, 3);
        
        return $out;
    }

    public function getBuyPriceAttribute($price)
    {
        return Money::CHF($price);
    }

    public function getInitialDateAttribute($initialDate)
    {
        return Carbon::parse($initialDate)->format('d.m.Y');
    }

    public function brand()
    {
        return $this->carModel->brand();
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function seller()
    {
        return $this->belongsTo(Contact::class, 'seller_contact_id');
    }

    public function buyer()
    {
        return $this->hasOneThrough(Contract::class, Contact::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function carPayment()
    {
        return $this->hasManyThrough(CarPayment::class, Contract::class);
    }

    public function scopeSoldThisYear($query)
    {
        return $query->whereDate('sold_at', '>=', Carbon::today()->format('Y'));
    }

    public function scopeSoldCars($query)
    {
        return $query->whereDate('sold_at', '>=', Carbon::today()->format('Y'));
    }

    public function scopeUnsoldCars($query)
    {
        return $query->whereDate('sold_at', );
    }

    public function scopeOrderByInitialDate($query)
    {
        $query->orderBy('initial_date');
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
