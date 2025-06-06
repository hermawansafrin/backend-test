<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;

/**
 * @OA\Info(
 *  version="1.0.0",
 *  title="Order Management System API's",
 *  description= "How to login:
 *   Login using the `/authentication/login` endpoint with your email and password.
 *   Copy the token from the response, specifically from `data.token.value`.
 *   Click the Authorize button in the top right corner.
 *   Enter the token in the format: `Bearer <access_token>`.
 *   Click the Authorize button."
 * )
 *
 * @OA\OpenApi(
 *  @OA\Server(
 *   description="LIVE server",
 *   url="/api/v1/",
 *  )
 * )
 *
 * @OA\SecurityScheme(
 *  securityScheme="Bearer",
 *  type="apiKey",
 *  name="Authorization",
 *  in="header",
 * )
 */

class BaseController extends Controller
{
    use ResponseTrait;
}
