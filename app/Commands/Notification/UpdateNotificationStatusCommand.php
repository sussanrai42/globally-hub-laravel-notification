<?php

namespace App\Commands\Notification;

use App\Bus\Command;
use App\Enums\NotificationStatus;

class UpdateNotificationStatusCommand extends Command
{
    public function __construct(
        public readonly string $notificationId,
        public readonly NotificationStatus $status
    ) {}
}
