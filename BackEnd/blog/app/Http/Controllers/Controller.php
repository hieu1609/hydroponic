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
