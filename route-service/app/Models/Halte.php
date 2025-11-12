<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class halte extends Model
{
    protected $table = 'halte';
    protected $fillable = ['rute_id','nama_halte','urutan'];

    public function rute()
    {
        return $this->belongsTo(rute::class, 'rute_id');
    }
}
