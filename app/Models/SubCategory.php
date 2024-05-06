<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function typeCategory(): BelongsTo
    {
        return $this->belongsTo(TypeCategory::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }
}
