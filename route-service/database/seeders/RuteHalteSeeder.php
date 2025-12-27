<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rute;
use App\Models\Halte;

class RuteHalteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================================
        // Koridor 1D: Leuwipanjang – Soreang
        // ==========================================================
        // PERBAIKAN: Gunakan Rute (Huruf Besar), bukan rute
        $r1 = Rute::create([
            'nama_rute'   => 'Koridor 1D Leuwipanjang – Soreang',
            'titik_awal'  => 'Terminal Leuwipanjang',
            'titik_akhir' => 'Pengendapan Bus Soreang',
            'jadwal'      => [
                'jam_operasional' => 'Senin–Minggu, 04.40–20.30',
                'rute_teks'       => 'Terminal Leuwipanjang hingga Pengendapan Bus Soreang',
                'headway_teks'    => 'Setiap 15–20 menit',
                'catatan'         => null
            ],
        ]);

        $halte1 = [
            'Grand Hotel Pasundan',
            'Festival City Link',
            'SPBU Pasir Koja',
            'Hotel Soreang',
            'Plaza Pemkab Bandung',
            'Pasar Ikan Modern',
            'SAMSAT Soreang',
            'Geo Dipa Energi',
            'Pengendapan Bus Soreang',
        ];

        foreach ($halte1 as $i => $nama) {
            // PERBAIKAN: Gunakan Halte (Huruf Besar)
            Halte::create([
                'rute_id'    => $r1->id,
                'nama_halte' => $nama,
                'urutan'     => $i + 1,
            ]);
        }

        // ==========================================================
        // Koridor 2: Kota Baru Parahyangan – Alun-alun Bandung
        // ==========================================================
        // PERBAIKAN: Gunakan Rute (Huruf Besar)
        $r2 = Rute::create([
            'nama_rute'   => 'Koridor 2 Kota Baru Parahyangan – Alun-alun Bandung',
            'titik_awal'  => 'Kota Baru Parahyangan, Padalarang',
            'titik_akhir' => 'Alun-alun Kota Bandung',
            'jadwal'      => [
                'jam_operasional' => 'Senin–Minggu, 04.30–20.00',
                'rute_teks'       => 'Kota Baru Parahyangan, Padalarang hingga Alun-alun Kota Bandung',
                'headway_teks'    => 'Setiap 10–15 menit',
                'catatan'         => null
            ],
        ]);

        $halte2 = [
            'Wahoo Waterworld',
            'Bale Pare',
            'Masjid Ar-Ridwan',
            'BRI Cimahi',
            'RSUD Cibabat',
            'Dungus Cariang',
            'RS Kebon Jati',
            'Lembong',
            'Alun-alun Bandung',
        ];

        foreach ($halte2 as $i => $nama) {
            // PERBAIKAN: Gunakan Halte (Huruf Besar)
            Halte::create([
                'rute_id'    => $r2->id,
                'nama_halte' => $nama,
                'urutan'     => $i + 1,
            ]);
        }

        // ==========================================================
        // Koridor 3: Baleendah – BEC
        // ==========================================================
        // PERBAIKAN: Gunakan Rute (Huruf Besar)
        $r3 = Rute::create([
            'nama_rute'   => 'Koridor 3 Baleendah – BEC',
            'titik_awal'  => 'Baleendah / Masjid Jami Baitul Huda',
            'titik_akhir' => 'Bandung Electronic Center (BEC) – Jl. Supratman',
            'jadwal'      => [
                'jam_operasional'        => 'Senin–Sabtu, 04.30–20.00',
                'jam_operasional_minggu' => 'Minggu, 16.00–20.00',
                'rute_teks'              => 'Baleendah & Masjid Jami Baitul Huda hingga Mal BEC Jl. Supratman',
                'headway_teks'           => 'Setiap 10–15 menit',
                'catatan'                => null
            ],
        ]);

        $halte3 = [
            'Masjid Al-Amanah',
            'Masjid Jami Baitul Huda',
            'Borma Bojongsoang',
            'Pasar Kordon',
            'PT LEN Industri',
            'Taman Tegallega',
            'Yogya Kepatihan',
            'Alun-alun Bandung',
            'BEC (Bandung Electronic Center)',
        ];

        foreach ($halte3 as $i => $nama) {
            // PERBAIKAN: Gunakan Halte (Huruf Besar)
            Halte::create([
                'rute_id'    => $r3->id,
                'nama_halte' => $nama,
                'urutan'     => $i + 1,
            ]);
        }
    }
}