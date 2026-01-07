<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;
use GraphQL\Error\Error;

class AddRouteMutation
{
    public function __invoke($_, array $args)
    {
        // 1. Tembak ke endpoint GRAPHQL service rute
        $url = env('ROUTE_SERVICE_URL', 'http://route-service:8000') . '/graphql';

        $response = Http::post($url, [
            'query' => '
                mutation($name: String!, $origin: String!, $destination: String!, $jadwal: String) {
                    createRute(name: $name, origin: $origin, destination: $destination, jadwal: $jadwal) {
                        id
                        name
                        origin
                        destination
                    }
                }
            ',
            'variables' => $args,
        ]);

        // 2. Cek error koneksi
        if ($response->failed()) {
            throw new Error("Gagal connect ke Route Service: " . $response->body());
        }

        $data = $response->json();

        // 3. Cek error validasi dari GraphQL Service Rute
        if (isset($data['errors'])) {
            throw new Error($data['errors'][0]['message']);
        }

        // 4. Balikin data
        return $data['data']['createRute'];
    }
}