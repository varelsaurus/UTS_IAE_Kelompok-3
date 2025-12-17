<?php

namespace App\GraphQL\Resolvers;

use Illuminate\Support\Facades\Http;

class BusRouteResolver
{
    // $root adalah data Bus yang sedang diproses
    public function __invoke($root, array $args)
    {
        $routeId = $root['route_id'];
        
        // Panggil Route Service berdasarkan ID
        $url = env('ROUTE_SERVICE_URL', 'http://localhost:8002') . "/api/routes/{$routeId}";
        $response = Http::get($url);

        return $response->successful() ? $response->json() : null;
    }
}