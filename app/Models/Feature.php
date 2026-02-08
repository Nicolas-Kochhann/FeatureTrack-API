<?php

namespace App\Models;

use App\Enums\FeatureStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    /** @use HasFactory<\Database\Factories\FeatureFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "links",
    ];

    protected $casts = [
        "links" => "array",
        "status" => FeatureStatus::class,
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }
}
