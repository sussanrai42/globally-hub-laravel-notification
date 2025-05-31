<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ExceptionHandle;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ExceptionHandle;

    public function render($request, Throwable $e)
    {
        return $this->handleException($e);
    }
}
