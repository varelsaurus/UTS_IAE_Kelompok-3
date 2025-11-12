<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="API Transport (Rute & Halte)",
 *   version="1.0.0",
 *   description="Dokumentasi OpenAPI untuk layanan Rute & Halte."
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="Base URL dari APP_URL"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 *
 * @OA\Parameter(
 *   parameter="Id",
 *   name="id",
 *   in="path",
 *   required=true,
 *   @OA\Schema(type="string")
 * )
 */
final class OpenApi {}
