<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http", "https"},
 *     host="localhost:800/hydroponic/BackEnd/blog/public",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="L5 Swagger API"
 *     )
 * )
 */

/**
 * @SWG\Post(
 *     path="/auth",
 *     description="Create account",
 *     tags={"Auth"},
 *     summary="Sign Up",
 *     security={{"jwt":{}}},
 *      @SWG\Parameter(
 *          name="body",
 *          description="Sign up account",
 *          required=true,
 *          in="body",
 *          @SWG\Schema(
 *              @SWG\Property(
 *                  property="username",
 *                  type="string",
 *              ),
 *              @SWG\Property(
 *                  property="email",
 *                  type="string",
 *              ),
 *              @SWG\Property(
 *                  property="password",
 *                  type="string",
 *              ),
 *              @SWG\Property(
 *                  property="confirmPassword",
 *                  type="string",
 *              ),
 *          ),
 *      ),
 *      @SWG\Response(response=200, description="Successful"),
 *      @SWG\Response(response=401, description="Unauthorized"),
 *      @SWG\Response(response=403, description="Forbidden"),
 *      @SWG\Response(response=404, description="Not Found"),
 *      @SWG\Response(response=422, description="Unprocessable Entity"),
 *      @SWG\Response(response=500, description="Internal Server Error"),
 * )
 */