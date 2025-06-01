<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Abstracts\BaseRequest;
use App\Rules\ContactNumberRule;
use App\Rules\CountryRule;

class RegisterUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country' => ['required', 'string', new CountryRule],
            'contact_number' => ['required', 'string', new ContactNumberRule],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
