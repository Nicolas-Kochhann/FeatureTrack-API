<?php

namespace App\Authorization;

use App\Models\User;
use App\Enums\UserProjectRole;
use Illuminate\Support\Facades\Gate;

class FeatureGate {

    public static function register(): void
    {

        Gate::define('feature.update/delete', function (User $user, int $featureId) 
        {
            return $user->projects()->whereHas('features', function($q) use ($featureId)
                {
                    $q->whereKey($featureId);
                })
                ->wherePivotIn('role',
                [
                    UserProjectRole::OWNER,
                    UserProjectRole::LEADER
                ])
                ->exists();
        });

        Gate::define('feature.view', function (User $user, int $featureId) 
        {
            return $user->projects()->whereHas('features', function($q) use ($featureId)
                {
                    $q->whereKey($featureId);
                })
                ->exists();
        });

        Gate::define('feature.create', function (User $user, int $projectId)
        {
            return $user->projects()
                ->whereKey($projectId)
                ->wherePivotIn('role', 
                [
                    UserProjectRole::OWNER,
                    UserProjectRole::LEADER
                ])
                ->exists();
        });

        Gate::define('feature.list', function (User $user, int $projectId)
        {
            return $user->projects()
                ->whereKey($projectId)
                ->exists();
        });

    }

}