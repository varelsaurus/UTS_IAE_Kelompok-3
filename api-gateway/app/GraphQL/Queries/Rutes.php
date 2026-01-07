<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class Rutes
{
    public function __invoke($_, array $args)
    {
        // PERHATIKAN URL INI:
        // 'route-service' = nama service di docker-compose
        // '8000' = port internal container (bukan 8002)
        $response = Http::post('http://route-service:8000/graphql', [
            'query' => '
                query {
                    rutes {
                        id
                        name
                        origin
                        destination
                        jadwal {
                            jam_operasional
                            headway_teks
                            catatan
                        }
                    }
                }
            ',
        ]);
        
        if ($response->failed()) {
            return [];
        }

        return $response->json()['data']['rutes'] ?? [];
    }
}