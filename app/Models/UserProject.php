<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Enums\UserProjectRole;

class UserProject extends Pivot
{
    protected $table = 'users_projects';

    protected $fillable = [
        'role',
    ];

    protected $casts = [
        'role' => UserProjectRole::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
