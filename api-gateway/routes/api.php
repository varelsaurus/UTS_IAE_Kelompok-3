<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

Route::any('/buses/{any?}', [GatewayController::class, 'buses'])->where('any', '.*');
Route::any('/rute/{any?}', [GatewayController::class, 'routes'])->where('any', '.*');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
