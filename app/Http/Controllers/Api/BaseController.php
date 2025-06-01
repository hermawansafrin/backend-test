<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;

/**
 * @OA\Info(
 *  version="1.0.0",
 *  title="Order Management System API's",
 *  description= "Cara login:
 *   Login menggunakan endpoint `/authentication/singleLogin` dengan email dan password Anda.
 *   Salin token dari respon yang dihasilkan, pada bagian `data.access_token`.
 *   Klik tombol Authorize di pojok kanan atas.
 *   Masukkan token, dengan format:  `Bearer <access_token>`.
 *   Klik tombol Authorize."
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
