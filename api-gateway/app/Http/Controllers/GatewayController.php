<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; 

class GatewayController extends Controller
{
    protected function forward(Request $request, string $targetBase)
    {
        $path = $request->getRequestUri();
        $url = rtrim($targetBase, '/') . $path;

        Log::info("Gateway Forward: URI='{$request->getRequestUri()}', Path='{$path}', URL='{$url}'");

        $resp = Http::withHeaders($request->headers->all())
            ->send($request->method(), $url, [
                'query' => $request->query(),
                'json' => $request->isJson() ? $request->json()->all(): null,
                'form_params' => $request->isJson() ? null : $request->all(),
            ]);

        Log::info("Gateway Response: Status={$resp->status()}");
            
        return response($resp->body(), $resp->status())
            ->withHeaders($resp->headers());
    }

    public function buses(Request $request)
    {
        return $this->forward($request, config('services.bus.url'));
    }

    public function routes(Request $request)
    {
        return $this->forward($request, config('services.route.url'));
    }
}