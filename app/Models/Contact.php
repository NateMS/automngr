<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\ContractType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getFullTitleAttribute()
    {
        return implode(', ', array_filter([$this->company, $this->name]));
    }

    public function getFullCityAttribute()
    {
        return $this->zip . ' ' . $this->city;
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
                    $query->where('firstname', 'like', '%' . $part . '%')
                        ->orWhere('lastname', 'like', '%' . $part . '%')
                        ->orWhere('company', 'like', '%' . $part . '%')
                        ->orWhere('email', 'like', '%' . $part . '%')
                        ->orWhere('zip', 'like',  $part . '%')
                        ->orWhere('city', 'like', '%' . $part . '%')
                        ->orWhere('address', 'like', '%' . $part . '%')
                        ->orWhere('phone', 'like', '%' . $part . '%');
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
