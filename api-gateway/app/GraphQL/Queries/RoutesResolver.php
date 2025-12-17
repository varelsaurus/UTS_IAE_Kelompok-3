<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class RoutesResolver
{
    public function __invoke($_, array $args)
    {
        $url = env('ROUTE_SERVICE_URL', 'http://127.0.0.1:8002') . '/api/rute';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            $rutes = isset($data['data']) ? $data['data'] : $data;

            return array_map(function ($rute) {
                return [
                    'id' => $rute['id'],
                    // MAPPING SESUAI DATABASE KAMU
                    'name' => $rute['nama_rute'],     // nama_rute -> name
                    'origin' => $rute['titik_awal'],  // titik_awal -> origin
                    'destination' => $rute['titik_akhir'], // titik_akhir -> destination
                ];
            }, $rutes);
        }

        return [];
    }
}