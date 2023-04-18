<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            throw new UnauthorizedHttpException('Unauthorized');
        } else {
            return route('login');
        }
    }

    /**
     * Determine if the request has a valid token or accept header.
     */
    protected function isValidTokenOrAcceptHeader($request)
    {
        return $request->bearerToken() || $request->hasHeader('Accept', 'application/json');
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($this->isValidTokenOrAcceptHeader($request)) {
            throw new UnauthorizedHttpException('Unauthorized');
        } else {
            parent::unauthenticated($request, $guards);
        }
    }
}

