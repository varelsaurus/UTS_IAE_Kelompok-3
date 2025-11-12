<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

Route::get('/ping', fn() => response()->json(['ok' => true]));

// BUS service (8002)
Route::any('/buses/{any?}', [GatewayController::class, 'buses'])->where('any', '.*');

// ROUTE service (8001)
Route::any('/routes/{any?}', [GatewayController::class, 'routes'])->where('any', '.*');
Route::any('/rute/{any?}', [GatewayController::class, 'routes'])->where('any', '.*');
