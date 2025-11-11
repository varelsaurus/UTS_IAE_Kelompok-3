<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\HalteController;

// Rute
Route::get   ('/rute',        [RuteController::class, 'index']);
Route::get   ('/rute/{id}',   [RuteController::class, 'tampil']);
Route::post  ('/rute',        [RuteController::class, 'tambah']);
Route::put   ('/rute/{id}',   [RuteController::class, 'ubah']);
Route::delete('/rute/{id}',   [RuteController::class, 'hapus']);

// Halte
Route::get   ('/rute/{rute_id}/halte', [HalteController::class, 'daftar']);
Route::post  ('/rute/{rute_id}/halte', [HalteController::class, 'tambah']);
Route::put   ('/halte/{id}',           [HalteController::class, 'ubah']);
Route::delete('/halte/{id}',           [HalteController::class, 'hapus']);
