<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model {
    protected $fillable = ['code', 'route_id', 'capacity', 'lat', 'lng'];
}
