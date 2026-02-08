<?php

namespace App\Enums;

enum UserProjectRole: string
{
    case OWNER = 'owner';
    case LEADER = 'leader';
    case MEMBER = 'member';
    case OBSERVER = 'observer';
}
