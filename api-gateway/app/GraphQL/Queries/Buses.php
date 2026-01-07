<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class Buses
{
    public function __invoke($_, array $args)
    {
        // PERHATIKAN URL INI:
        // 'bus-service' = nama service di docker-compose
        // '8000' = port internal container (bukan 8001)
        $response = Http::post('http://bus-service:8000/graphql', [
            'query' => '
                query {
                    buses {
                        id
                        code
                        capacity
                        route_id
                        lat
                        lng
                        created_at
                        updated_at
                    }
                }
            ',
        ]);

        // Cek jika request gagal
        if ($response->failed()) {
            return []; // Atau throw error
        }

        return $response->json()['data']['buses'] ?? [];
    }
}