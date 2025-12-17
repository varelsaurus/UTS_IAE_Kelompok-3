<?php
namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class RouteResolver
{
    public function __invoke($_, array $args)
    {
        $id = $args['id'];
        $url = env('ROUTE_SERVICE_URL', 'http://127.0.0.1:8002') . "/api/rute/{$id}";
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            $rute = isset($data['data']) ? $data['data'] : $data;

            return [
                'id' => $rute['id'],
                // MAPPING SESUAI DATABASE KAMU
                'name' => $rute['nama_rute'],
                'origin' => $rute['titik_awal'],
                'destination' => $rute['titik_akhir'],
            ];
        }

        return null;
    }
}