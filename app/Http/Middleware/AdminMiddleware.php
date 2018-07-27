<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;

class AdminMiddleware
{

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $user = $this->jwt->user()) {
            return response([
                "message" => "Unauthorized. Please provide a valid token"
            ], 401);
        }

        if (! $user->admin) {
            return response([
                "message" => "You do not have permission to access this route"
            ], 401);
        }

        return $next($request);

    }
}
