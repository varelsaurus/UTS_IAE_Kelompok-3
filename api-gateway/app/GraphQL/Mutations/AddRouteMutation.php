<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;
use GraphQL\Error\Error;

class AddRouteMutation
{
    public function __invoke($_, array $args)
    {
        // Pastikan URL-nya benar. Biasanya di Laravel route API itu plural (jamak) atau singular
        // Cek routes/api.php kamu nanti. Asumsi sementara '/api/rute'
        $baseUrl = env('ROUTE_SERVICE_URL', 'http://route-service:8000'); 
        $url = $baseUrl . '/api/rute';
        
        try {
            // PERBAIKAN PENTING:
            // Tambahkan header 'Accept: application/json'
            // Supaya kalau validasi gagal, dia balikin JSON error, bukan redirect HTML.
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($url, [
                'nama_rute' => $args['name'],
                'titik_awal' => $args['origin'],
                'titik_akhir' => $args['destination'],
            ]);

        } catch (\Exception $e) {
            throw new Error("Koneksi Error: " . $e->getMessage());
        }

        $data = $response->json();

        // Cek jika sukses (Status code 200/201)
        if ($response->successful()) {
            // Handle format respon yang dibungkus 'data' atau tidak
            $rute = $data['data'] ?? $data;

            return [
                'id' => $rute['id'],
                'name' => $rute['nama_rute'],
                'origin' => $rute['titik_awal'],
                'destination' => $rute['titik_akhir']
            ];
        }

        // Kalau gagal (misal validasi error 422), tampilkan pesan error dari JSON-nya
        // Jangan tampilkan HTML body
        $errorMessage = $data['message'] ?? $response->body();
        
        // Jika detail validasi ada
        if (isset($data['errors'])) {
            $errorMessage .= ' - ' . json_encode($data['errors']);
        }

        throw new Error("Gagal membuat rute: " . $errorMessage);
    }
}