<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Queries\GetCountriesQuery;
use App\Http\Controllers\Controller;
use App\Bus\Contracts\QueryBusContract;

class CountryController extends Controller
{
    public function __construct(
        protected QueryBusContract $queryBus,
    ) {}

    public function index(): JsonResponse
    {
        try {
            return $this->successResponse(
                data: $this->queryBus->ask(new GetCountriesQuery)
            );
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
