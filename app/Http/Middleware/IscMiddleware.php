<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;

class IscMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hash = $request->get("hash");

        if (!$hash) {
            throw ValidationException::withMessages([
                "hash" => [
                    "The attribute hash is required"
                ]
            ]);
        }

        if (md5(config("services.isc_hash_key")) !== $request->get("hash")) {
            throw new UnauthorizedException("unauthorized", 403);
        }

        return $next($request);
    }
}
