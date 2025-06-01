<?php

namespace App\Models;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $type
 * @property-read string $contact
 * @property-read int $user_id
 * @property-read string $title
 * @property-read string $message
 * @property-read ?array $payload
 * @property-read string $status
 * @property-read array $error
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @property-read User $user
 */
class Notification extends BaseModel
{
    protected $fillable = [
        'type',
        'contact',
        'user_id',
        'title',
        'message',
        'payload',
        'status',
        'error',
    ];

    protected $casts = [
        'payload' => 'array',
        'error' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
