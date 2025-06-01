<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(
        Notification $notification,
    ) {
        parent::__construct($notification);
    }
}
