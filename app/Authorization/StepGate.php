<?php

namespace App\Authorization;

use App\Models\User;
use App\Enums\UserProjectRole;
use Illuminate\Support\Facades\Gate;

class StepGate {

    public static function register(): void
    {

        Gate::define('step.update/delete', function (User $user, int $stepId) 
        {
            return $user->projects()->whereHas('features.steps', function($q) use ($stepId)
            {
                $q->whereKey($stepId);
            })
            ->wherePivotIn('role', [
                UserProjectRole::OWNER,
                UserProjectRole::LEADER,
                UserProjectRole::MEMBER
            ])
            ->exists();
        });

        Gate::define('step.view', function (User $user, int $stepId) 
        {
            return $user->projects()->whereHas('features.steps', function($q) use ($stepId)
            {
                $q->whereKey($stepId);
            })
            ->exists();
        });

        Gate::define('step.list', function (User $user, int $featureId)
        {
            return $user->projects()->whereHas('features', function($q) use ($featureId)
            {
                $q->whereKey($featureId);
            })
            ->exists();
        });

        Gate::define('step.create', function (User $user, int $featureId) 
        {
            return $user->projects()->whereHas('features', function($q) use ($featureId)
            {
                $q->whereKey($featureId);
            })
            ->wherePivotIn('role', [
                UserProjectRole::OWNER,
                UserProjectRole::LEADER,
                UserProjectRole::MEMBER
            ])
            ->exists();
        });

    }

}