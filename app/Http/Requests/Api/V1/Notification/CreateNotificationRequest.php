<?php

namespace App\Http\Requests\Api\V1\Notification;

use App\Abstracts\BaseRequest;
use App\Enums\NotificationType;
use Illuminate\Validation\Rule;
use App\DTOs\Request\CreateNotificationData;
use App\Exceptions\ContactNumberMissingException;
use App\Repositories\Interfaces\UserRepositoryInterface;

class CreateNotificationRequest extends BaseRequest
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(NotificationType::getValues())],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDto(): CreateNotificationData
    {
        $user = auth()->user();

        if ($this->type === NotificationType::SMS->value) {
            $contact = $user->contact_number;
        } elseif ($this->type === NotificationType::EMAIL->value) {
            $contact = $user->email;
        }

        if (!$contact) {
            throw new ContactNumberMissingException('Contact not found to send notification');
        }

        return CreateNotificationData::from([
            'type' => $this->type,
            'userId' => $user->id,
            'contact' => $contact,
            'title' => $this->title,
            'message' => $this->message,
            'payload' => []
        ]);
    }
}
