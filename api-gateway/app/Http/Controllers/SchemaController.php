<?php

namespace App\Http\Controllers;

/**
 * @OA\Schema(
 *   schema="Bus",
 *   title="Bus",
 *   description="Model Bus",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="code", type="string", example="B-01"),
 *   @OA\Property(property="route_id", type="integer", example=1),
 *   @OA\Property(property="capacity", type="integer", example=50),
 *   @OA\Property(property="lat", type="number", format="double", nullable=true, example=-6.2088),
 *   @OA\Property(property="lng", type="number", format="double", nullable=true, example=106.8456),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-09T12:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-09T12:00:00Z"),
 *   required={"id", "code", "route_id", "capacity"}
 * )
 * 
 * @OA\Schema(
 *   schema="BusCreate",
 *   title="Bus Create",
 *   description="Data untuk membuat Bus baru",
 *   @OA\Property(property="code", type="string", example="B-01"),
 *   @OA\Property(property="route_id", type="integer", example=1),
 *   @OA\Property(property="capacity", type="integer", example=50),
 *   @OA\Property(property="lat", type="number", format="double", nullable=true, example=-6.2088),
 *   @OA\Property(property="lng", type="number", format="double", nullable=true, example=106.8456),
 *   required={"code", "route_id", "capacity"}
 * )
 * 
 * @OA\Schema(
 *   schema="BusUpdate",
 *   title="Bus Update",
 *   description="Data untuk memperbarui Bus",
 *   @OA\Property(property="code", type="string", example="B-01"),
 *   @OA\Property(property="route_id", type="integer", example=1),
 *   @OA\Property(property="capacity", type="integer", example=50),
 *   @OA\Property(property="lat", type="number", format="double", nullable=true, example=-6.2088),
 *   @OA\Property(property="lng", type="number", format="double", nullable=true, example=106.8456)
 * )
 * 
 * @OA\Schema(
 *   schema="Route",
 *   title="Route",
 *   description="Model Rute (Ringkas)",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nama_rute", type="string", example="Koridor 1D Leuwipanjang – Soreang"),
 *   @OA\Property(property="titik_awal", type="string", example="Terminal Leuwipanjang"),
 *   @OA\Property(property="titik_akhir", type="string", example="Pengendapan Bus Soreang"),
 *   @OA\Property(property="keberangkatan_pertama", type="string", nullable=true, example="04:40"),
 *   @OA\Property(property="keberangkatan_terakhir", type="string", nullable=true, example="20:30"),
 *   @OA\Property(property="jam_operasional", type="string", nullable=true, example="Senin–Minggu, 04.40–20.30"),
 *   @OA\Property(property="headway", type="string", nullable=true, example="Setiap 15–20 menit"),
 *   required={"id", "nama_rute", "titik_awal", "titik_akhir"}
 * )
 * 
 * @OA\Schema(
 *   schema="Stop",
 *   title="Stop",
 *   description="Model Halte (Pemberhentian dalam Rute)",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="rute_id", type="integer", example=1),
 *   @OA\Property(property="nama_halte", type="string", example="Terminal Leuwipanjang"),
 *   @OA\Property(property="urutan", type="integer", example=1),
 *   required={"id", "rute_id", "nama_halte", "urutan"}
 * )
 * 
 * @OA\Schema(
 *   schema="RouteDetail",
 *   title="Route Detail",
 *   description="Model Rute lengkap dengan daftar halte",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nama_rute", type="string", example="Koridor 1D Leuwipanjang – Soreang"),
 *   @OA\Property(property="titik_awal", type="string", example="Terminal Leuwipanjang"),
 *   @OA\Property(property="titik_akhir", type="string", example="Pengendapan Bus Soreang"),
 *   @OA\Property(
 *     property="jadwal",
 *     type="object",
 *     example={"jam_operasional": "Senin–Minggu, 04.40–20.30", "headway_teks": "Setiap 15–20 menit"}
 *   ),
 *   @OA\Property(
 *     property="halte",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/Stop")
 *   ),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-09T12:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-09T12:00:00Z"),
 *   required={"id", "nama_rute", "titik_awal", "titik_akhir"}
 * )
 * 
 * @OA\Schema(
 *   schema="RouteCreate",
 *   title="Route Create",
 *   description="Data untuk membuat Rute baru",
 *   @OA\Property(property="nama_rute", type="string", example="Koridor 1D Leuwipanjang – Soreang"),
 *   @OA\Property(property="titik_awal", type="string", example="Terminal Leuwipanjang"),
 *   @OA\Property(property="titik_akhir", type="string", example="Pengendapan Bus Soreang"),
 *   @OA\Property(
 *     property="jadwal",
 *     type="object",
 *     example={"jam_operasional": "Senin–Minggu, 04.40–20.30", "headway_teks": "Setiap 15–20 menit"}
 *   ),
 *   @OA\Property(
 *     property="halte_daftar",
 *     type="array",
 *     @OA\Items(type="string"),
 *     example={"Terminal Leuwipanjang", "Festival City Link", "SPBU Pasir Koja"}
 *   ),
 *   required={"nama_rute", "titik_awal", "titik_akhir", "jadwal"}
 * )
 * 
 * @OA\Schema(
 *   schema="RouteUpdate",
 *   title="Route Update",
 *   description="Data untuk memperbarui Rute",
 *   @OA\Property(property="nama_rute", type="string", example="Koridor 1D Leuwipanjang – Soreang"),
 *   @OA\Property(property="titik_awal", type="string", example="Terminal Leuwipanjang"),
 *   @OA\Property(property="titik_akhir", type="string", example="Pengendapan Bus Soreang"),
 *   @OA\Property(
 *     property="jadwal",
 *     type="object",
 *     example={"jam_operasional": "Senin–Minggu, 04.40–20.30", "headway_teks": "Setiap 15–20 menit"}
 *   ),
 *   @OA\Property(
 *     property="halte_daftar",
 *     type="array",
 *     @OA\Items(type="string")
 *   )
 * )
 * 
 * @OA\Schema(
 *   schema="Error",
 *   title="Error",
 *   description="Error response",
 *   @OA\Property(property="message", type="string", example="Not Found"),
 *   @OA\Property(property="exception", type="string", example="ModelNotFoundException"),
 *   required={"message"}
 * )
 */
class SchemaController
{
}
