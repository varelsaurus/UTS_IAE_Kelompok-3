<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class halte extends Model
{
    protected $table = 'halte';
    protected $fillable = ['rute_id','nama_halte','urutan'];
}
