<?php

namespace App\Models;

use App\Enums\PaymentType;
use App\Models\Contract;
use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date',
        'type',
        'contract_id',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class)->withTrashed();
    }

    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }

    public function getAmountAttribute($amount)
    {
        return Money::CHF($amount);
    }

    public function getAmountRawAttribute()
    {
        return $this->amount;
    }

    public function getTypeAttribute($type)
    {
        switch ($type) {
            case PaymentType::Transaction():
                return 'Banküberweisung';
            case PaymentType::Cash():
                return 'Barzahlung';
            default:
                return 'Überweisung via Cembra';
        }; 
    }

    public function getTypeTextAttribute()
    {
        switch ($this->type) {
            case 'Banküberweisung':
                return 'via Banküberweisung erhalten';
            case 'Barzahlung':
                return 'in bar erhalten';
            default:
                return 'via Cembra-Überweisung erhalten';
        }; 
    }

    public function getPrintLinkAttribute()
    {
        return route('payments.print', [$this->contract->id, $this->id]);
    }

    public function getDeleteLinkAttribute()
    {
        return route('payments.destroy', [$this->contract->id, $this->id]);
    }

    public function scopeCashOnly($query)
    {
        $query->where('type', PaymentType::Cash());
    }

    public function scopeTransactionOnly($query)
    {
        $query->where('type', PaymentType::Transaction());
    }

    public function scopeCembraOnly($query)
    {
        $query->where('type', PaymentType::Cembra());
    }
}
