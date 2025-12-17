<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class BusResolver
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // Ambil ID dari query GraphQL (bus(id: 1))
        $id = $args['id'];

        // Tembak ke Bus Service (REST API) endpoint /api/buses/{id}
        // Pastikan URL-nya benar mengarah ke port 8001
        $url = env('BUS_SERVICE_URL', 'http://localhost:8001') . "/api/buses/{$id}";
        
        $response = Http::get($url);

        // Jika data tidak ditemukan di Service Bus, kembalikan null
        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}