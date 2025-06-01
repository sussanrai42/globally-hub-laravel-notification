<?php

namespace App\Commands\Notification;

use App\Bus\Command;
use App\DTOs\Request\CreateNotificationData;

class CreateNotificationCommand extends Command
{
    public function __construct(
        public readonly CreateNotificationData $data,
    ) {}
}
