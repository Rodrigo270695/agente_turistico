<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
}
