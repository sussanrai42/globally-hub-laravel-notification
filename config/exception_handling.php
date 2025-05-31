<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return [
    /*
    |--------------------------------------------------------------------------
    | Fatal Exception Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines configurations for fatal exceptions.
    | You can enable Slack notifications for fatal exceptions and specify
    | the webhook URL in the environment file.
    |
    */
    'fatal_exceptions' => [
        // Add a slack notification configuration here if you want
    ],

    /*
    |--------------------------------------------------------------------------
    | Non-Fatal Exceptions
    |--------------------------------------------------------------------------
    |
    | List all exceptions that should not be considered fatal here.
    | These exceptions will be handled separately without triggering fatal notifications.
    |
    */
    'non_fatal_exceptions' => [
        NotFoundHttpException::class,
        ModelNotFoundException::class,
        RouteNotFoundException::class,
        ValidationException::class,
        UnauthorizedException::class,
        AuthorizationException::class,
        UnauthorizedHttpException::class,
        AuthenticationException::class,
        MethodNotAllowedHttpException::class,
        ThrottleRequestsException::class,
    ],
];
