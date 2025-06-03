<?php

namespace App\Listeners;

use Bschmitt\Amqp\Facades\Amqp;
use App\Abstracts\BaseListener;
use App\Events\NotificationCreatedEvent;

class NotificationCreatedListener extends BaseListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationCreatedEvent $event): void
    {
        $payload = [
            "type" => $event->notification->type,
            "id" => $event->notification->id,
            "to" => $event->notification->contact,
            "title" => $event->notification->title,
            'message' => $event->notification->message,
            "userId" => $event->notification->user_id,
            "payload" => $event->notification->payload
        ];

        Amqp::publish('notification-queue-routing-key', json_encode($payload), [
            'content_type' => 'application/json',
            'delivery_mode' => 2, // Make message persistent
            'exchange' => 'laravel-notification-exchange',
            'queue' => 'notify-laravel-queue',
            'exchange_type' => 'direct',
        ]);
    }
}
