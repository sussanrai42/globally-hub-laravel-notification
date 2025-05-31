<?php

declare(strict_types=1);

namespace App\Bus\Contracts;

use App\Bus\Command as BusCommand;

interface CommandBusContract
{
    public function dispatch(BusCommand $command): mixed;

    public function register(array $map): void;
}
