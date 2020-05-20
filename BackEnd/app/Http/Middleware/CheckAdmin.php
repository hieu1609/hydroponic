<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Http\Controllers\BaseApiController;

class CheckAdmin extends BaseApiController
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
      if(!$request->user->admin)
      {
        return $this->responseErrorCustom('user_priority', 403);
      }
    } catch (Exception $exception) {
      return $this->responseErrorException($exception->getMessage(),99999, 500);
    }
    return $next($request);
  }
}
