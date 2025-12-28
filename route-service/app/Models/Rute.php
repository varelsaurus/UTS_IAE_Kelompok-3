<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Halte;

class Rute extends Model
{
    protected $table = 'rute';
    protected $fillable = ['nama_rute','titik_awal','titik_akhir','jadwal'];
    protected $casts = ['jadwal' => 'array'];

    public function halte()
    {
        // pakai halte::class (huruf kecil), bukan Halte::class
        return $this->hasMany(halte::class, 'rute_id')->orderBy('urutan');
    }
}
