<?php

namespace App\Authorization;

use App\Models\User;
use App\Enums\UserProjectRole;
use Illuminate\Support\Facades\Gate;

class InviteGate {

    public static function register(): void
    {

        Gate::define('invite.update/delete', function (User $user, int $inviteId) 
        {
            return $user->sentInvites()->whereKey($inviteId)->exists();
        });

        Gate::define('invite.respond', function (User $user, int $inviteId)
        {
            return $user->receivedInvites()->whereKey($inviteId)->exists();
        });

        Gate::define('invite.view', function (User $user, int $inviteId) 
        {
            if($user->sentInvites()->whereKey($inviteId)->exists()){
                return true;
            } else if ($user->receivedInvites()->whereKey($inviteId)->exists()){
                return true;
            }

            return false;
        });

        Gate::define('invite.create', function (User $user, int $projectId)
        {
            return $user->projects()
                ->whereKey($projectId)
                ->wherePivotIn('role', [
                    UserProjectRole::OWNER,
                    UserProjectRole::LEADER
                ])
                ->exists();
        });

    }

}