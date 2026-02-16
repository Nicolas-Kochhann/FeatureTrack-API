<?php

namespace App\Enums;

enum InviteStatus: string
{
    case REJECTED = "rejected";
    case PENDING = "pending";
    case ACCEPTED = "accepted";
}
