<?php

namespace App\GraphQL\Resolvers;

use Illuminate\Support\Facades\Http;

class BusRouteResolver
{
    public function __invoke($root, array $args)
        {
            // Debugging: Cek apakah route_id terbaca
            // dd($root); 

            // Pastikan key 'route_id' sesuai dengan nama kolom di database Bus kamu
            $routeId = $root['route_id']; 

            // Pastikan URL mengarah ke port 8002
            $url = env('ROUTE_SERVICE_URL', 'http://localhost:8002') . "/api/rute/{$routeId}";
            
            $response = Http::get($url);

            return $response->successful() ? $response->json() : null;
        }
    }