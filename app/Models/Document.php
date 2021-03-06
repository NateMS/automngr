<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'internal_name',
        'extension',
        'size',
        'documentable_type',
        'documentable_id',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

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
        if ($size / 1024 < 1) {
            return $size.' B';
        }

        if ($size / 1024 / 1024 < 1) {
            return floor($size / 1024).' KB';
        }

        return floor($size / 1024 / 1024).' MB';
    }

    public function getLinkAttribute()
    {
        return route('documents.show', $this->id);
    }

    public function getPathAttribute()
    {
        return public_path("documents/{$this->documentable_type}/{$this->documentable->id}/{$this->internal_name}");
    }
}
