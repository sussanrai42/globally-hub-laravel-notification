<?php

namespace App\Commands;

use App\Bus\Command;
use App\DTOs\Request\CreateUserData;

class CreateUserCommand extends Command
{
    public function __construct(
        public readonly CreateUserData $createUserData
    ) {}
}
