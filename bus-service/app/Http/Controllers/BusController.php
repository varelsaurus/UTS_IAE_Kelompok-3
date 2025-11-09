<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *   title="Bus Service API",
 *   version="1.0.0",
 *   description="API for managing buses in Public Transportation Tracker"
 * )
 */
use App\Models\Bus; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BusController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/buses",
     *   summary="List all buses",
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function index() { 
        return response()->json(Bus::all()); 
    }

    /**
     * @OA\Get(
     *   path="/api/buses/{id}",
     *   summary="Get bus by ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id) { 
        return Bus::findOrFail($id); 
    }

    /**
     * @OA\Post(
     *   path="/api/buses",
     *   summary="Create new bus",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"code","route_id","capacity"},
     *       @OA\Property(property="code", type="string"),
     *       @OA\Property(property="route_id", type="integer"),
     *       @OA\Property(property="capacity", type="integer"),
     *       @OA\Property(property="lat", type="number", nullable=true),
     *       @OA\Property(property="lng", type="number", nullable=true)
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created")
     * )
     */
    public function store(Request $r) { 
        return Bus::create($r->all()); 
    }

    /**
     * @OA\Put(
     *   path="/api/buses/{id}",
     *   summary="Update bus by ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"code","route_id","capacity"},
     *       @OA\Property(property="code", type="string"),
     *       @OA\Property(property="route_id", type="integer"),
     *       @OA\Property(property="capacity", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated")
     * )
     */
    public function update(Request $r, $id) {
        $bus = Bus::findOrFail($id); 
        $bus->update($r->all()); 
        return $bus;
    }

    /**
     * @OA\Delete(
     *   path="/api/buses/{id}",
     *   summary="Delete bus by ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Deleted")
     * )
     */
    public function destroy($id) { 
        $b = Bus::findOrFail($id); 
        $b->delete(); 
        return response()->json($b); 
    }
    
    /**
     * @OA\Get(
     *   path="/api/buses/{id}/with-route",
     *   summary="Get bus with route details",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Bus with route")
     * )
     */
    public function withRoute($id) {
        $bus = Bus::findOrFail($id);
        $routeSvc = rtrim(config('services.route_service.url', env('ROUTE_SERVICE_URL')), '/');
        $route = Http::get("{$routeSvc}/api/routes/{$bus->route_id}")->json();
        return response()->json(['bus' => $bus, 'route' => $route]);
    }
}