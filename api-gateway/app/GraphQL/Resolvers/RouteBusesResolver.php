<?php

namespace App\GraphQL\Resolvers;

use Illuminate\Support\Facades\Http;

class RouteBusesResolver
{
    public function __invoke($root, array $args)
    {
        // Ambil ID dari Rute yang sedang dibuka
        $routeId = $root['id'];

        // Tembak ke Bus Service (Ambil SEMUA bus dulu)
        // Gunakan 127.0.0.1 port 8001 (Bus Service)
        $url = env('BUS_SERVICE_URL', 'http://127.0.0.1:8001') . '/api/buses';
        
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            
            // Cek apakah data dibungkus 'data'
            $allBuses = isset($data['data']) ? $data['data'] : $data;

            // FILTER MANUAL: Cari bus yang route_id nya sama dengan routeId rute ini
            $filteredBuses = array_filter($allBuses, function ($bus) use ($routeId) {
                // Pastikan route_id ada dan sama
                return isset($bus['route_id']) && $bus['route_id'] == $routeId;
            });

            return $filteredBuses;
        }

        return [];
    }
}