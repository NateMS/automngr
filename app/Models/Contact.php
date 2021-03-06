<?php

namespace App\Models;

use App\Enums\ContractType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'address',
        'zip',
        'city',
        'country',
        'company',
        'email',
        'notes',
    ];

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getNameAttribute()
    {
        return implode(' ', array_filter([$this->lastname, $this->firstname]));
    }

    public function getTitleAttribute()
    {
        if ($this->company != '') {
            return $this->company;
        }

        return $this->name;
    }

    public function getDeletedAtAttribute($deleted_at)
    {
        if ($deleted_at) {
            return Carbon::parse($deleted_at)->format('d.m.Y');
        }

        return null;
    }

    public function getCountryAttribute($country)
    {
        return $country ?? '';
    }

    public function getFullTitleAttribute()
    {
        return implode(', ', array_filter([$this->company, $this->name]));
    }

    public function getFullTitleWithAddressAttribute()
    {
        return implode(', ', array_filter([$this->full_title, $this->address, $this->full_city]));
    }

    public function getFullCityAttribute()
    {
        return $this->zip.' '.$this->city;
    }

    public function getLinkAttribute()
    {
        return route('contacts.show', $this);
    }

    public function scopeOrderByName($query, $direction)
    {
        $query->orderBy('lastname', $direction)->orderBy('firstname', $direction);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function buyContracts()
    {
        return $this->contracts()->buyContracts();
    }

    public function sellContracts()
    {
        return $this->contracts()->sellContracts();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $parts = explode(' ', $search);
            foreach ($parts as $part) {
                $query->where(function ($query) use ($part) {
                    $query->where('firstname', 'like', '%'.$part.'%')
                        ->orWhere('lastname', 'like', '%'.$part.'%')
                        ->orWhere('company', 'like', '%'.$part.'%')
                        ->orWhere('email', 'like', '%'.$part.'%')
                        ->orWhere('zip', 'like', $part.'%')
                        ->orWhere('city', 'like', '%'.$part.'%')
                        ->orWhere('address', 'like', '%'.$part.'%')
                        ->orWhere('phone', 'like', '%'.$part.'%');
                });
            }
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
