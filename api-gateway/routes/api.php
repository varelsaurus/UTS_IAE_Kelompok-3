<?php

use App\Http\Controllers\GatewayController;

Route::any('/bus/{any?}', [GatewayController::class, 'buses'])->where('any', '.*');
Route::any('/buses/{any?}', [GatewayController::class, 'buses'])->where('any', '.*');

Route::any('/rute/{any?}', [GatewayController::class, 'routes'])->where('any', '.*');
Route::any('/routes/{any?}', [GatewayController::class, 'routes'])->where('any', '.*');
