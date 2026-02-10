<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at"=> "datetime",
    ];

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
