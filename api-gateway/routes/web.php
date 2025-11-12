<?php

use Illuminate\Support\Facades\Route;

// ============================
// 🌐 Halaman Utama (Dashboard)
// ============================
Route::get('/', function () {
    return view('dashboard');
});

// ============================
// 📚 API Documentation (Swagger)
// ============================
Route::get('/docs', function () {
    return view('swagger-docs');
});

// ============================
// 🚌 Halaman CRUD Bus
// ============================
Route::get('/bus/tambah', function () {
    return view('bus_tambah');
});

Route::get('/bus/edit', function () {
    return view('bus_edit');
});

// ============================
// 🚏 Halaman CRUD Rute
// ============================
Route::get('/rute', function () {
    return view('rute');
});

Route::get('/rute/tambah', function () {
    return view('rute_tambah');
});

Route::get('/rute/edit', function () {
    return view('rute_edit');
});
