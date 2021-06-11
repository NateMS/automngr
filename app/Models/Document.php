<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'internal_name',
        'extension',
        'size',
        'contract_id',
    ];

    public function getCreatedAtAttribute($created_at)
    {
        return Carbon::parse($created_at)->format('d.m.Y');
    }

    public function getExtensionAttribute($extension)
    {
        return strtoupper($extension);
    }

    public function getSizeAttribute($size)
    {
        if ($size / 1024 / 1024 < 1) {
            return (string)floor($size / 1024) . " KB";
        }
        return (string)floor($size / 1024 / 1024) . " MB";
    }

    public function getLinkAttribute()
    {
        return route('documents.show', $this->id);
    }

    public function getPathAttribute()
    {
        return public_path("documents/contracts/{$this->contract->id}/{$this->internal_name}");
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class)->withTrashed();
    }
}
