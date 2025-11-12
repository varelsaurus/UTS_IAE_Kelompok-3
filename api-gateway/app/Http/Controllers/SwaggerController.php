<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *   title="Public Transportation Tracker - API Gateway",
 *   version="1.0.0",
 *   description="Central API Gateway untuk mengelola Bus dan Rute perjalanan transportasi umum. Gateway ini meneruskan request ke microservices bus-service dan route-service.",
 *   contact={
 *     "name": "Support Team",
 *     "email": "support@transportation.local"
 *   },
 *   license={
 *     "name": "MIT"
 *   }
 * )
 * 
 * @OA\Server(
 *   url="http://localhost:8000",
 *   description="Local Development Server"
 * )
 * 
 * @OA\Tag(
 *   name="Buses",
 *   description="Operasi pengelolaan Bus"
 * )
 * 
 * @OA\Tag(
 *   name="Routes",
 *   description="Operasi pengelolaan Rute"
 * )
 * 
 * @OA\PathItem(path="/api/buses")
 * @OA\PathItem(path="/api/rute")
 */
class SwaggerController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/buses",
     *   operationId="getBuses",
     *   tags={"Buses"},
     *   summary="Daftar semua bus",
     *   description="Mengambil daftar lengkap semua bus yang terdaftar di sistem",
     *   @OA\Response(
     *     response=200,
     *     description="Daftar bus berhasil diambil",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Bus")
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal mengambil data bus dari service"
     *   )
     * )
     */
    public function getBuses() {}

    /**
     * @OA\Get(
     *   path="/api/buses/{id}",
     *   operationId="getBusById",
     *   tags={"Buses"},
     *   summary="Ambil detail bus berdasarkan ID",
     *   description="Mengambil informasi detail bus tertentu berdasarkan ID",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID Bus",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Detail bus berhasil diambil",
     *     @OA\JsonContent(ref="#/components/schemas/Bus")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Bus tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal mengambil detail bus"
     *   )
     * )
     */
    public function getBusById() {}

    /**
     * @OA\Post(
     *   path="/api/buses",
     *   operationId="createBus",
     *   tags={"Buses"},
     *   summary="Tambah bus baru",
     *   description="Membuat bus baru di sistem",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Data bus baru",
     *     @OA\JsonContent(ref="#/components/schemas/BusCreate")
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Bus berhasil ditambahkan",
     *     @OA\JsonContent(ref="#/components/schemas/Bus")
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Validasi gagal atau data tidak lengkap"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal menambahkan bus"
     *   )
     * )
     */
    public function createBus() {}

    /**
     * @OA\Put(
     *   path="/api/buses/{id}",
     *   operationId="updateBus",
     *   tags={"Buses"},
     *   summary="Perbarui data bus",
     *   description="Memperbarui informasi bus yang sudah ada berdasarkan ID",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID Bus",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="Data bus yang diperbarui",
     *     @OA\JsonContent(ref="#/components/schemas/BusUpdate")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Bus berhasil diperbarui",
     *     @OA\JsonContent(ref="#/components/schemas/Bus")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Bus tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validasi gagal"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal memperbarui bus"
     *   )
     * )
     */
    public function updateBus() {}

    /**
     * @OA\Delete(
     *   path="/api/buses/{id}",
     *   operationId="deleteBus",
     *   tags={"Buses"},
     *   summary="Hapus bus",
     *   description="Menghapus bus dari sistem berdasarkan ID",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID Bus",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Bus berhasil dihapus",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="Bus deleted successfully")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Bus tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal menghapus bus"
     *   )
     * )
     */
    public function deleteBus() {}

    /**
     * @OA\Get(
     *   path="/api/rute",
     *   operationId="getRoutes",
     *   tags={"Routes"},
     *   summary="Daftar semua rute",
     *   description="Mengambil daftar lengkap semua rute perjalanan yang terdaftar",
     *   @OA\Response(
     *     response=200,
     *     description="Daftar rute berhasil diambil",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Route")
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal mengambil data rute dari service"
     *   )
     * )
     */
    public function getRoutes() {}

    /**
     * @OA\Get(
     *   path="/api/rute/{id}",
     *   operationId="getRouteById",
     *   tags={"Routes"},
     *   summary="Ambil detail rute berdasarkan ID",
     *   description="Mengambil informasi detail rute tertentu beserta daftar halte-nya",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID Rute",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Detail rute berhasil diambil",
     *     @OA\JsonContent(ref="#/components/schemas/RouteDetail")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Rute tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal mengambil detail rute"
     *   )
     * )
     */
    public function getRouteById() {}

    /**
     * @OA\Post(
     *   path="/api/rute",
     *   operationId="createRoute",
     *   tags={"Routes"},
     *   summary="Tambah rute baru",
     *   description="Membuat rute perjalanan baru di sistem",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Data rute baru",
     *     @OA\JsonContent(ref="#/components/schemas/RouteCreate")
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Rute berhasil ditambahkan",
     *     @OA\JsonContent(ref="#/components/schemas/RouteDetail")
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Validasi gagal atau data tidak lengkap"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal menambahkan rute"
     *   )
     * )
     */
    public function createRoute() {}

    /**
     * @OA\Put(
     *   path="/api/rute/{id}",
     *   operationId="updateRoute",
     *   tags={"Routes"},
     *   summary="Perbarui data rute",
     *   description="Memperbarui informasi rute yang sudah ada",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID Rute",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="Data rute yang diperbarui",
     *     @OA\JsonContent(ref="#/components/schemas/RouteUpdate")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Rute berhasil diperbarui",
     *     @OA\JsonContent(ref="#/components/schemas/RouteDetail")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Rute tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validasi gagal"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal memperbarui rute"
     *   )
     * )
     */
    public function updateRoute() {}

    /**
     * @OA\Delete(
     *   path="/api/rute/{id}",
     *   operationId="deleteRoute",
     *   tags={"Routes"},
     *   summary="Hapus rute",
     *   description="Menghapus rute dari sistem berdasarkan ID",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID Rute",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Rute berhasil dihapus",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="Route deleted successfully")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Rute tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal menghapus rute"
     *   )
     * )
     */
    public function deleteRoute() {}

    /**
     * @OA\Get(
     *   path="/api/rute/{rute_id}/halte",
     *   operationId="getRouteStops",
     *   tags={"Routes"},
     *   summary="Daftar halte dalam rute",
     *   description="Mengambil daftar semua halte (pemberhentian) dalam rute tertentu, diurutkan sesuai urutan perjalanan",
     *   @OA\Parameter(
     *     name="rute_id",
     *     in="path",
     *     description="ID Rute",
     *     required=true,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Daftar halte berhasil diambil",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(ref="#/components/schemas/Stop")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Rute tidak ditemukan"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Gagal mengambil data halte"
     *   )
     * )
     */
    public function getRouteStops() {}
}
