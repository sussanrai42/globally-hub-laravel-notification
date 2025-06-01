<?php

namespace App\Http\Requests\Api\V1\Isc;

use App\Abstracts\BaseRequest;
use Illuminate\Validation\Rule;
use App\Enums\NotificationStatus;

class UpdateNotificationStatusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([NotificationStatus::FAILED->value, NotificationStatus::COMPLETED->value]),
            ]
        ];
    }
}
