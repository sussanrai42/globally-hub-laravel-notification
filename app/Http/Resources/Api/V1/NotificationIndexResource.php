<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use App\Abstracts\BaseResource;
use App\Http\Resources\Api\UserDetailResource;

class NotificationIndexResource extends BaseResource

{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'contact' => $this->contact,
            'title' => $this->title,
            'message' => $this->message,
            'payload' => $this->payload,
            'status' => $this->status,
            'user' => UserDetailResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
