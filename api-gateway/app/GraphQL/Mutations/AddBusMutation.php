<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;
use GraphQL\Error\Error;

class AddBusMutation
{
    public function __invoke($_, array $args)
    {
        // 1. Tembak ke endpoint GRAPHQL service bus (Bukan /api/buses lagi)
        $url = env('BUS_SERVICE_URL', 'http://bus-service:8000') . '/graphql';

        $response = Http::post($url, [
            'query' => '
                mutation($code: String!, $capacity: Int!, $route_id: Int!, $lat: Float, $lng: Float) {
                    addBus(code: $code, capacity: $capacity, route_id: $route_id, lat: $lat, lng: $lng) {
                        id
                        code
                        capacity
                        route_id
                    }
                }
            ',
            'variables' => $args, // Masukkan input dari user langsung ke variables
        ]);

        // 2. Cek error koneksi
        if ($response->failed()) {
            throw new Error("Gagal connect ke Bus Service: " . $response->body());
        }

        $data = $response->json();

        // 3. Cek error validasi dari GraphQL Service Bus
        if (isset($data['errors'])) {
            throw new Error($data['errors'][0]['message']);
        }

        // 4. Balikin data
        return $data['data']['addBus'];
    }
}