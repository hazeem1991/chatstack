<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\Middleware\GetUserFromToken;

use Closure;
// use JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyJWTToken extends GetUserFromToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return response()->json(['token_not_provided'], 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }

        if (! $user) {
            return response()->json(['user_not_found'], 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
