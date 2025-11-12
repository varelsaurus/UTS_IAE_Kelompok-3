<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Rute",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="rute_1"),
 *   @OA\Property(property="nama_rute", type="string"),
 *   @OA\Property(property="titik_awal", type="string"),
 *   @OA\Property(property="titik_akhir", type="string"),
 *   @OA\Property(property="jadwal", type="array", @OA\Items(type="string", example="05:00")),
 *   @OA\Property(property="halte", type="array", @OA\Items(ref="#/components/schemas/Halte")),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *   schema="RuteCreate",
 *   type="object",
 *   required={"nama_rute","titik_awal","titik_akhir","jadwal"},
 *   @OA\Property(property="nama_rute", type="string", minLength=3),
 *   @OA\Property(property="titik_awal", type="string"),
 *   @OA\Property(property="titik_akhir", type="string"),
 *   @OA\Property(property="jadwal", type="array", @OA\Items(type="string", example="05:00")),
 *   @OA\Property(property="halte_ids", type="array", @OA\Items(type="string"))
 * )
 *
 * @OA\Schema(
 *   schema="RuteUpdate",
 *   type="object",
 *   @OA\Property(property="nama_rute", type="string", minLength=3),
 *   @OA\Property(property="titik_awal", type="string"),
 *   @OA\Property(property="titik_akhir", type="string"),
 *   @OA\Property(property="jadwal", type="array", @OA\Items(type="string")),
 *   @OA\Property(property="halte_ids", type="array", @OA\Items(type="string"))
 * )
 *
 * @OA\Schema(
 *   schema="Halte",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="hlt_1"),
 *   @OA\Property(property="nama_halte", type="string"),
 *   @OA\Property(property="urutan", type="integer", minimum=1),
 *   @OA\Property(property="rute_id", type="string", example="rute_1")
 * )
 *
 * @OA\Schema(
 *   schema="HalteUpdate",
 *   type="object",
 *   required={"nama_halte"},
 *   @OA\Property(property="nama_halte", type="string"),
 *   @OA\Property(property="urutan", type="integer", minimum=1)
 * )
 *
 * @OA\Schema(
 *   schema="Error",
 *   type="object",
 *   @OA\Property(property="code", type="string"),
 *   @OA\Property(property="message", type="string")
 * )
 */
final class Schemas {}
