<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\InviteStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invite extends Model
{
    
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'project_id',
        'status'
    ];

    protected $casts = [
        'status' => InviteStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

}
