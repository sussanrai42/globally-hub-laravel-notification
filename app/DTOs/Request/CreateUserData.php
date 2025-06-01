<?php

namespace App\DTOs\Request;

use App\Abstracts\BaseData;
use Illuminate\Http\Request;

class CreateUserData extends BaseData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $country,
        public readonly string $contactNumber,
        public readonly string $password
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->name,
            $request->email,
            $request->country,
            $request->contact_number,
            $request->password,
        );
    }
}
