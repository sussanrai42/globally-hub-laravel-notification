<?php

namespace App\Enums;

use App\Traits\HasEnumValue;

enum NotificationStatus: string
{
    use HasEnumValue;
    
    case PENDING = "pending";
    case COMPLETED = "completed";
    case FAILED = "failed";
}
