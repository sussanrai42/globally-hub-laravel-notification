<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected const CACHE_TTL = 3600;

    public static function boot()
    {
        parent::boot();
        static::deleting(function (self $model) {
            Cache::forget("personal_access:token_{$model->token}");
            Cache::forget("personal_access:tokenable_{$model->tokenable_type}_{$model->tokenable_id}");
        });
    }

    /**
     * Find the token instance matching the given token.
     *
     * @param  string  $token
     * @return static|null
     */
    public static function findToken($token)
    {
        $hashedToken = hash(
            'sha256',
            str_contains($token, '|') ? explode('|', $token, 2)[1] : $token
        );
        return Cache::remember(
            key: "personal_access:token_{$hashedToken}",
            ttl: now()->addSeconds(self::CACHE_TTL),
            callback: function () use ($token) {
                return parent::findToken($token);
            }
        );
    }

    public function gettokenableAttribute(): ?User
    {
        return Cache::remember(
            key: "personal_access:tokenable_{$this->tokenable_type}_{$this->tokenable_id}",
            ttl: now()->addSeconds(self::CACHE_TTL),
            callback: function () {
                return parent::tokenable()->first();
            }
        );
    }
}
