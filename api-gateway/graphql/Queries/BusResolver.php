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
        // Ambil ID dari argument GraphQL
        $id = $args['id'];

        // Tembak ke Bus Service (REST API) endpoint /api/buses/{id}
        // Pastikan portnya benar (8001 untuk bus service)
        $url = env('BUS_SERVICE_URL', 'http://localhost:8001') . "/api/buses/{$id}";
        
        $response = Http::get($url);

        // Jika gagal/not found, kembalikan null agar GraphQL tidak error sistem
        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}