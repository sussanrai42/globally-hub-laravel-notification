<?php

declare(strict_types=1);

namespace App\Bus;

use Illuminate\Bus\Dispatcher;
use App\Bus\Contracts\QueryBusContract;

class IlluminateQueryBus implements QueryBusContract
{
    public function __construct(
        protected Dispatcher $bus,
    ) {}

    public function ask(Query $query): mixed
    {
        return $this->bus->dispatch($query);
    }

    public function register(array $map): void
    {
        $this->bus->map($map);
    }
}
