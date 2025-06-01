<?php

namespace App\QueryHandlers;

use App\Bus\QueryHandler;
use App\Queries\GetCountriesQuery;

class GetCountriesQueryHandler extends QueryHandler
{
    public function handle(GetCountriesQuery $getCountriesQuery): array
    {
        return config('countries');
    }
}
