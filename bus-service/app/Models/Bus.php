<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit (biar gak nebak-nebak)
    protected $table = 'buses';

    // 2. Daftar kolom yang boleh diisi
    protected $fillable = [
        'code', 
        'route_id', 
        'capacity', 
        'lat', 
        'lng'
    ];

    // 3. Casting (PENTING BUAT GRAPHQL)
    // Ini memaksa data keluar sebagai Angka (Integer/Float), bukan String.
    protected $casts = [
        'route_id' => 'integer',
        'capacity' => 'integer',
        'lat' => 'float',
        'lng' => 'float',
    ];
}