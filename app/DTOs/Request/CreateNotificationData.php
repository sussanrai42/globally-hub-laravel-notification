<?php

namespace App\DTOs\Request;

use App\Abstracts\BaseData;
use App\Enums\NotificationType;

class CreateNotificationData extends BaseData
{
    public function __construct(
        public readonly NotificationType $type,
        public readonly string $contact,
        public readonly string $userId,
        public readonly string $title,
        public readonly string $message,
        public readonly array $payload,
    ) {}
}
