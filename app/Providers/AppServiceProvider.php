<?php

namespace App\Providers;

use App\Bus\IlluminateQueryBus;
use App\Bus\IlluminateCommandBus;
use App\Bus\Contracts\QueryBusContract;
use Illuminate\Support\ServiceProvider;
use App\Bus\Contracts\CommandBusContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        CommandBusContract::class => IlluminateCommandBus::class,
        QueryBusContract::class => IlluminateQueryBus::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerCommandHandlers();
        $this->registerQueryHandlers();
    }

    protected function registerCommandHandlers(): void
    {
        $commandBus = app(CommandBusContract::class);
        $commandBus->register([
            // Command::class => CommandHandler::class
        ]);
    }

    protected function registerQueryHandlers(): void
    {
        $queryBus = app(QueryBusContract::class);
        $queryBus->register([
            // Query::class => QueryHandler::class,
        ]);
    }
}
