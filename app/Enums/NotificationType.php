<?php

namespace App\Enums;

use App\Traits\HasEnumValue;

enum NotificationType: string
{
    use HasEnumValue;

    case SMS = 'sms';
    case EMAIL = 'email';
}
