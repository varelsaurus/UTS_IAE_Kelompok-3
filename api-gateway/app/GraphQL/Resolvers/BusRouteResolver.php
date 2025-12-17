<?php
namespace App\GraphQL\Resolvers;

use Illuminate\Support\Facades\Http;

class BusRouteResolver
{
    public function __invoke($root, array $args)
    {
        // Ambil route_id dari data Bus
        $routeId = $root['route_id'];
        
        // Tembak ke Route Service
        $url = env('ROUTE_SERVICE_URL', 'http://127.0.0.1:8002') . "/api/rute/{$routeId}";
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            $rute = isset($data['data']) ? $data['data'] : $data;

            return [
                'id' => $rute['id'],
                // MAPPING SESUAI DATABASE KAMU (PENTING!)
                'name' => $rute['nama_rute'],      // nama_rute -> name
                'origin' => $rute['titik_awal'],   // titik_awal -> origin
                'destination' => $rute['titik_akhir'], // titik_akhir -> destination
            ];
        }

        return null;
    }
}