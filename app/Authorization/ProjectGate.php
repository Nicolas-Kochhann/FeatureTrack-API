<?php

namespace App\Authorization;

use App\Models\User;
use App\Enums\UserProjectRole;
use Illuminate\Support\Facades\Gate;

class ProjectGate {

    public static function register(): void
    {

        Gate::define('project.update/delete', function (User $user, int $projectId) 
        {
            return $user->projects()
                ->whereKey($projectId)
                ->wherePivot('role', UserProjectRole::OWNER)
                ->exists();
        });

        Gate::define('project.view', function (User $user, int $projectId) 
        {
            return $user->projects()
                ->whereKey($projectId)
                ->exists();
        });

    }

}