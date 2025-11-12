<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\HalteController;

Route::get('/rute',            [RuteController::class, 'index']);
Route::get('/rute/{id}',       [RuteController::class, 'tampil']);
Route::post('/rute',           [RuteController::class, 'tambah']);
Route::put('/rute/{id}',       [RuteController::class, 'ubah']);
Route::delete('/rute/{id}',    [RuteController::class, 'hapus']);

// Ubah baris ini:
Route::get('/rute/{rute_id}/halte', [RuteController::class, 'daftar']);

// Granular Halte (tetap ada)
Route::get('/halte',           [HalteController::class, 'index']);
Route::get('/halte/{id}',      [HalteController::class, 'show']);
Route::put('/halte/{id}',      [HalteController::class, 'update']);
Route::delete('/halte/{id}',   [HalteController::class, 'destroy']);
