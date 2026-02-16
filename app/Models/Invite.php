<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\InviteStatus;

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

}
