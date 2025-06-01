<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case PENDING = "pending";
    case COMPLETED = "completed";
    case FAILED = "failed";
}
