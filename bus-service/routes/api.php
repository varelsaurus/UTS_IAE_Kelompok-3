<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusController;

Route::get('/test', function () { return response()->json(['ok' => true]); });
Route::get('/buses', [BusController::class, 'index']);
Route::get('/buses/{id}', [BusController::class, 'show']);
Route::post('/buses', [BusController::class, 'store']);
Route::put('/buses/{id}', [BusController::class, 'update']);
Route::delete('/buses/{id}', [BusController::class, 'destroy']);
Route::get('/buses/{id}/with-route', [BusController::class, 'withRoute']);