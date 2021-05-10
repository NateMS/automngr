<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document_type',
        'car_id',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
