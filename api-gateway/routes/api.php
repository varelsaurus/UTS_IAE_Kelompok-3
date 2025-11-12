<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

/*
|--------------------------------------------------------------------------
| API Routes for Gateway
|--------------------------------------------------------------------------
|
| Semua request yang diawali /api/buses atau /api/routes akan diteruskan
| ke microservice masing-masing (bus-service dan route-service)
| lewat GatewayController.
|
*/

Route::any('/buses/{any?}', [GatewayController::class, 'buses'])
    ->where('any', '.*');

Route::any('/rute/{any?}', [GatewayController::class, 'routes'])
    ->where('any', '.*');
