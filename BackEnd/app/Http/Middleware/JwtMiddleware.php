<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Http\Controllers\BaseApiController;

class JwtMiddleware extends BaseApiController
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return BaseApiController::responseErrorCustom("middleware_users_not_found", 401);
            }
            $request->user = $user;
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return BaseApiController::responseErrorCustom("tokens_invalid", 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return BaseApiController::responseErrorCustom("tokens_expired", 401);
            } else {
                return BaseApiController::responseErrorCustom("tokens_not_found", 401);
            }
        }
        return $next($request);
    }
}
