<?php

namespace App\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use App\Models\Contract;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $type == PaymentType::Transaction() ? 'Banküberweisung' : 'Barzahlung';
    }

    public function getTypeTextAttribute()
    {
        return $this->type == 'Banküberweisung' ? 'via Banküberweisung erhalten' : 'in bar erhalten';
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
}
