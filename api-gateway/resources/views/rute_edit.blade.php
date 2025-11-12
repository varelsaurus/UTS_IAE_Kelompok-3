<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    protected function forward(Request $request, string $baseUrl)
    {
        // Hilangkan prefix /api dari URL gateway
        $path = preg_replace('#^/api#', '', $request->getRequestUri());
        $url = rtrim($baseUrl, '/') . '/api' . $path;

        logger('Forwarding to URL: ' . $url);

        try {
            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'query' => $request->query(),
                    'json' => $request->isJson() ? $request->json()->all() : null,
                    'form_params' => $request->isJson() ? null : $request->all(),
                ]);

            return response($response->body(), $response->status())
                ->withHeaders($response->headers());
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'target_url' => $url,
            ], 500);
        }
    }

    public function buses(Request $request)
    {
        return $this->forward($request, config('services.bus_service.url'));
    }

    public function routes(Request $request)
    {
        return $this->forward($request, config('services.route_service.url'));
    }
}
