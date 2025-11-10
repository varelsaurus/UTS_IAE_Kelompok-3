<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rute extends Model
{
    protected $table = 'rute'; // <— WAJIB agar tidak cari 'rutes'
    protected $fillable = ['nama_rute','titik_awal','titik_akhir','jadwal'];
    protected $casts = ['jadwal' => 'array'];
}
