<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class BusesResolver
{
    public function __invoke($_, array $args)
    {
        // Panggil Bus Service (REST API) yang kemarin sudah jalan
        $url = env('BUS_SERVICE_URL', 'http://localhost:8001') . '/api/buses';
        $response = Http::get($url);

        return $response->json(); // Kembalikan array data bus
    }
}