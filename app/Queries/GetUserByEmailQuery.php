<?php

namespace App\Queries;

use App\Bus\Query;

class GetUserByEmailQuery extends Query
{
    public function __construct(
        public readonly string $email
    ) {}
}
