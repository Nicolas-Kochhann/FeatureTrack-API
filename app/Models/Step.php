<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    /** @use HasFactory<\Database\Factories\StepFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'completed',
    ];

    protected $casts = [
        'completed'=> 'boolean',
    ];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

}
