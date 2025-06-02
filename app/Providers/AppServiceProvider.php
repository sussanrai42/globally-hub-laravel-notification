<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use App\Bus\IlluminateQueryBus;
use App\Bus\IlluminateCommandBus;
use App\Queries\GetCountriesQuery;
use App\Commands\CreateUserCommand;
use App\Models\PersonalAccessToken;
use App\Queries\GetUserByEmailQuery;
use App\Queries\GetNotificationsQuery;
use App\Bus\Contracts\QueryBusContract;
use Illuminate\Support\ServiceProvider;
use App\Bus\Contracts\CommandBusContract;
use App\QueryHandlers\GetCountriesQueryHandler;
use App\QueryHandlers\GetUserByEmailQueryHandler;
use App\CommandHandlers\CreateUserCommandHandler;
use App\QueryHandlers\GetNotificationsQueryHandler;
use App\Commands\Notification\CreateNotificationCommand;
use App\Commands\Notification\UpdateNotificationStatusCommand;
use App\CommandHandlers\Notification\CreateNotificationCommandHandler;
use App\CommandHandlers\Notification\UpdateNotificationStatusCommandHandler;

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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->registerCommandHandlers();
        $this->registerQueryHandlers();
    }

    protected function registerCommandHandlers(): void
    {
        $commandBus = app(CommandBusContract::class);
        $commandBus->register([
            // Command::class => CommandHandler::class
            CreateUserCommand::class => CreateUserCommandHandler::class,
            CreateNotificationCommand::class => CreateNotificationCommandHandler::class,
            UpdateNotificationStatusCommand::class => UpdateNotificationStatusCommandHandler::class
        ]);
    }

    protected function registerQueryHandlers(): void
    {
        $queryBus = app(QueryBusContract::class);
        $queryBus->register([
            // Query::class => QueryHandler::class,
            GetUserByEmailQuery::class => GetUserByEmailQueryHandler::class,
            GetCountriesQuery::class => GetCountriesQueryHandler::class,
            GetNotificationsQuery::class => GetNotificationsQueryHandler::class
        ]);
    }
}
