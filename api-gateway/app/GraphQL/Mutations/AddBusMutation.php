<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;
use GraphQL\Error\Error; // Tambahkan ini

class AddBusMutation
{
    public function __invoke($_, array $args)
    {
        // Pastikan URL mengarah ke nama service di docker-compose
        $url = env('BUS_SERVICE_URL', 'http://bus-service:8000') . '/api/buses';
        
        try {
            $response = Http::post($url, [
                'code' => $args['code'],
                'capacity' => $args['capacity'],
                'route_id' => $args['route_id'],
            ]);
        } catch (\Exception $e) {
            throw new Error("Koneksi ke Bus Service Gagal: " . $e->getMessage());
        }

        if ($response->successful()) {
            $data = $response->json();
            $bus = isset($data['data']) ? $data['data'] : $data;

            return [
                'id' => $bus['id'],
                'code' => $bus['code'],
                'capacity' => $bus['capacity'],
                'route_id' => $bus['route_id']
            ];
        }

        // PERBAIKAN: Jangan return null! Lempar Error supaya ketahuan kenapa gagal.
        // Ini akan menampilkan pesan error dari Bus Service (misal: "Route ID not found")
        throw new Error("Gagal membuat Bus: " . $response->body());
    }
}