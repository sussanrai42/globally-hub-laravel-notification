<?php

namespace App\Events;

use App\Abstracts\BaseEvent;
use App\Models\Notification;

class NotificationCreatedEvent extends BaseEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Notification $notification,
    ) {
        //
    }
}
