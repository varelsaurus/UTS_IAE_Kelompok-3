<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    protected function forward(Request $request, string $baseUrl)
    {
        // Ambil path dari request (tanpa query)
        $path = $request->getPathInfo();

        // Kalau path tidak diawali /api, tambahkan manual
        if (!str_starts_with($path, '/api')) {
            $path = '/api' . $path;
        }

        // Ganti /routes -> /rute biar cocok dengan route-service
        $path = str_replace('/api/routes', '/api/rute', $path);

        $url = rtrim($baseUrl, '/') . $path;

        logger()->debug('Forwarding to URL: ' . $url);

        try {
            $options = [
                'query' => $request->query(),
                'body'  => $request->getContent(),
            ];

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
            ])->send($request->method(), $url, $options);

            return response($response->body(), $response->status())
                ->header('Content-Type', $response->header('Content-Type'));
        } catch (\Throwable $e) {
            logger()->error('Gateway forward error: '.$e->getMessage(), ['url'=>$url]);
            return response()->json([
                'error' => $e->getMessage(),
                'target_url' => $url,
            ], 500);
        }
    }


    // ---------------- BUS SERVICE ----------------
    public function buses(Request $request)
    {
        return $this->forward($request, config('services.bus_service.url'));
    }

    // ---------------- ROUTE SERVICE ----------------
    public function routes(Request $request)
    {
        return $this->forward($request, config('services.route_service.url'));
    }
}
