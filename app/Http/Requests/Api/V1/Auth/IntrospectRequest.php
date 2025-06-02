<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Abstracts\BaseRequest;

class IntrospectRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
            ],
            'permission' => [
                'nullable',
                'string',
            ],
        ];
    }
}
