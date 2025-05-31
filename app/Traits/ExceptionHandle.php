<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\UniqueConstraintViolationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ExceptionHandle
{
    use ApiResponse;

    protected ?string $exceptionId = null;

    public function getModelName(ModelNotFoundException $exception): string
    {
        $model = ucfirst(Str::kebab(class_basename($exception->getModel())));

        return $model ? str_replace('-', ' ', $model) . ' not found' : $exception->getMessage();
    }

    public function handleException(object $exception, array $errors = [], bool $debug = true, bool $fatalNotification = true): JsonResponse
    {
        // Set the fatal exception log
        $this->setFatalExceptionLog($exception, $errors,  $fatalNotification);
        //return the exception response in json
        return $this->errorResponse(
            message: $this->getExceptionMessage($exception),
            status: $this->getExceptionCode($exception),
            errors: $this->getExceptionErrors($exception, $debug),
        );
    }

    public function getExceptionMessage(object $exception): string
    {
        $exceptionClass = $this->getExceptionClass($exception);
        return match ($exceptionClass) {
            ModelNotFoundException::class => $this->getModelName($exception),
            UniqueConstraintViolationException::class => 'Duplicate Entry.',
            QueryException::class => match ($exception->errorInfo[1]) {
                1062 => 'Duplicate Entry.',
                1451 => 'Cannot delete or update a parent row.',
                default => 'Something went wrong. Please try again later.',
            },
            default => $exception->getMessage(),
        };
    }

    public function getExceptionCode(object $exception)
    {
        $exceptionClass = $this->getExceptionClass($exception);
        return (int) match ($exceptionClass) {
            NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
            ModelNotFoundException::class => Response::HTTP_NOT_FOUND,
            RouteNotFoundException::class => Response::HTTP_NOT_FOUND,
            ValidationException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
            UnauthorizedException::class => Response::HTTP_UNAUTHORIZED,
            AuthorizationException::class => Response::HTTP_UNAUTHORIZED,
            UnauthorizedHttpException::class => Response::HTTP_FORBIDDEN,
            QueryException::class => Response::HTTP_INTERNAL_SERVER_ERROR,
            UniqueConstraintViolationException::class => Response::HTTP_INTERNAL_SERVER_ERROR,
            AuthenticationException::class => Response::HTTP_UNAUTHORIZED,
            MethodNotAllowedHttpException::class => Response::HTTP_METHOD_NOT_ALLOWED,
            ThrottleRequestsException::class => Response::HTTP_TOO_MANY_REQUESTS,
            default => $exception->getCode() != 0
                ? $exception->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    public function getExceptionErrors(object $exception, bool $debug = true): mixed
    {
        $exceptionClass = $this->getExceptionClass($exception);

        $exceptionData = match ($exceptionClass) {
            ValidationException::class => method_exists($exception, 'errors') ? $exception->errors() : null,
            default => null,
        };

        // Handle additional logic for the default case
        if ($exceptionClass !== ValidationException::class && $this->exceptionId) {
            $exceptionData = ['exceptionId' => $this->exceptionId];
            if ($debug && !app()->isProduction()) {
                $exceptionData["message"] = $exception->getMessage();
                $exceptionData["file"] = $exception->getFile();
                $exceptionData["line"] = $exception->getLine();
                $exceptionData["traceString"] = ($exception->getTrace());
                // $exceptionData["traceString"] = $exception->getTraceAsString();
            }
        }

        return $exceptionData;
    }

    public function setFatalExceptionLog(object $exception, array $errors = [], bool $fatalNotification = true): void
    {
        $exceptionClass = $this->getExceptionClass($exception);

        if (!in_array($exceptionClass, $this->getException())) {
            $this->exceptionId = Str::uuid()->toString() . '_' . time();

            $trace = $exception->getTrace();

            if ($exception instanceof QueryException) {
                $trace = array_merge($trace, [
                    'sql' => $exception->getSql(),
                    'bindings' => $exception->getBindings(),
                ]);
            }

            Log::error(
                message: $this->exceptionId,
                context: array_merge($errors, [
                    'auth_user_id' => auth()->id(),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'request_url_path' => request()->fullUrl(),
                    'request' => [
                        'parameters' => request()->query(),
                        'request_data' => request()->all(),
                        'headers' => request()->header(),
                    ],
                    'trace' => $trace,
                ]),
            );

            // Can send slack notification if you want here by writing slack notification code
        }
    }

    public function getExceptionClass(object $exception): string
    {
        return get_class($exception);
    }

    public function getException(): array
    {
        return config('exception_handling.non_fatal_exceptions', [
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
        ]);
    }

    /**
     * Get the slack webhook url for notification
     * @return string
     */
    public function getSlackWebhookUrl(): string
    {
        return config('exception_handling.fatal_exceptions.slack_notification.webhook_url');
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param array<string|int, mixed> $errors
     * @return array<string, mixed>
     */
    public function getSlackErrorDetail(object $exception, array $errors = []): array
    {
        $userId = auth()->id();

        if ($userId) {
            $errors['auth_user_id'] = $userId;
        }

        return array_merge(
            [
                'exception_id' => $this->exceptionId,
                'class' => self::class,
                'app_env' => app()->environment(),
                'request_url_path' => request()->fullUrl(),
                'request_method' => request()->method(),
            ],
            $errors,
            [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }
}
