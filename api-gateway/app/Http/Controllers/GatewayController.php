<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    /**
     * Forward request ke service target (Bus / Route)
     */
    protected function forward(Request $request, string $targetBase)
    {
        // Ambil path request (contoh: /api/buses → /buses)
        $path = '/' . trim(preg_replace('#^/api#', '', $request->getRequestUri()), '/');
        $url  = rtrim($targetBase, '/') . $path;

        // Kirim ulang request ke target service
        $resp = Http::withHeaders($request->headers->all())
            ->send($request->method(), $url, [
                'query' => $request->query(),
                'json' => $request->isJson() ? $request->json()->all() : null,
                'form_params' => $request->isJson() ? null : $request->all(),
            ]);

        // Balikkan respons dari service target ke client
        return response($resp->body(), $resp->status())
            ->withHeaders($resp->headers());
    }

    /**
     * Handle request ke /api/buses/*
     */
    public function buses(Request $r)
    {
        return $this->forward($r, config('services.bus.url'));
    }

    /**
     * Handle request ke /api/routes/*
     */
    public function routes(Request $r)
    {
        return $this->forward($r, config('services.route.url'));
    }
}
