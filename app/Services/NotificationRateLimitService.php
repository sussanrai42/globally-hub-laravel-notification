<?php

namespace App\Services;

use App\Abstracts\BaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class NotificationRateLimitService extends BaseService
{
    public function canCreateNotification(string $userId): bool
    {
        $key = "notification-create:user_$userId";
        $maxAttemps = config('app.notification.create.rate_limit', 100);
        $decayInSeconds = config('app.notification.create.decay_in_seconds', 60);

        // Check if the user has exceeded the rate limit
        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $seconds = RateLimiter::availableIn($key);
            Log::warning("User {$userId} has exceeded the create notification limit. Try again in {$seconds} seconds.");
            return false;
        }

        // Increment the attempt count
        RateLimiter::hit($key, (int) $decayInSeconds);

        return true;
    }
}
