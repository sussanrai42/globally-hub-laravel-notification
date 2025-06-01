<?php


namespace App\CommandHandlers\Notification;

use App\Bus\CommandHandler;
use App\Models\Notification;
use App\Events\NotificationCreatedEvent;
use App\Services\NotificationRateLimitService;
use App\Exceptions\RateLimitNotificationException;
use App\Commands\Notification\CreateNotificationCommand;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class CreateNotificationCommandHandler extends CommandHandler
{
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository,
        protected NotificationRateLimitService $notificationRateLimitService,
    ) {}

    public function handle(CreateNotificationCommand $command): Notification
    {
        if (!$this->notificationRateLimitService->canCreateNotification($command->data->userId)) {
            throw new RateLimitNotificationException('Rate limit exceeded for creating notifications', 421);
        }

        $notification = $this->notificationRepository->create([
            'user_id' => $command->data->userId,
            'type' => $command->data->type,
            'contact' => $command->data->contact,
            'title' => $command->data->title,
            'message' => $command->data->message,
            'payload' => $command->data->payload
        ]);

        event(new NotificationCreatedEvent($notification));

        return $notification;
    }
}
