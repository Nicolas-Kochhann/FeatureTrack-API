<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    /** @use HasFactory<\Database\Factories\StepFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'completed',
    ];

    public function markStepAsCompleted(): bool
    {
        return $this->update(['completed' => true]);
    }
}
