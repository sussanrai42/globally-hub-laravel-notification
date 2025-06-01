<?php

namespace App\Queries;

use App\Bus\Query;
use Illuminate\Http\Request;

class GetNotificationsQuery extends Query
{
    public function __construct(
        public readonly Request $request,
    ) {}
}
