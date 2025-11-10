<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/', 'rute');
Route::view('/rute/tambah', 'rute_tambah');
Route::view('/rute/edit', 'rute_edit'); 