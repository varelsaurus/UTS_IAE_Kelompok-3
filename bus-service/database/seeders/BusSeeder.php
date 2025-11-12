<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        Bus::create(['code' => 'B01', 'route_id' => 1, 'capacity' => 50, 'lat' => -6.2088, 'lng' => 106.8456]);
        Bus::create(['code' => 'B02', 'route_id' => 1, 'capacity' => 40, 'lat' => -6.1754, 'lng' => 106.8294]);
    }
}