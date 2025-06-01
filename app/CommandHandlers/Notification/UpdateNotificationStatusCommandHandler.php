<?php

namespace App\CommandHandlers\Notification;

use App\Bus\CommandHandler;
use App\Commands\Notification\UpdateNotificationStatusCommand;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class UpdateNotificationStatusCommandHandler extends CommandHandler
{
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function handle(UpdateNotificationStatusCommand $command): void
    {
        $this->notificationRepository->update($command->notificationId, [
            'status' => $command->status
        ]);
    }
}
