<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        Bus::updateOrCreate(['code' => 'B-01'], [
            'route_id' => 1,
            'capacity' => 40,
            'lat' => -6.9210000,
            'lng' => 107.6070000,
        ]);

        Bus::updateOrCreate(['code' => 'B-02'], [
            'route_id' => 2,
            'capacity' => 36,
            'lat' => -6.9300000,
            'lng' => 107.6000000,
        ]);
    }
}
