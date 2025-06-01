<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Abstracts\BaseRequest;

class UserLoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
