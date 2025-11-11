<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rute;   // model huruf kecil
use App\Models\halte;  // optional

class RuteController extends Controller
{

    public function daftar($rute_id)
    {
        // optional: pastikan rute ada
        rute::findOrFail($rute_id);

        // ambil halte untuk rute tsb, urut berdasarkan 'urutan'
        return halte::where('rute_id', $rute_id)
            ->orderBy('urutan')
            ->get(['id','rute_id','nama_halte','urutan']);
    }
    
    // GET /api/rute  -> daftar rute ringkas untuk tabel
    public function index()
    {
        return rute::with(['halte' => function($q){ $q->orderBy('urutan'); }])
            ->get()
            ->map(function ($r) {
                $j = $r->jadwal ?? [];
                $head = $j['headway_menit'] ?? null;

                return [
                    'id'                     => $r->id,
                    'nama_rute'              => $r->nama_rute,
                    'titik_awal'             => $r->titik_awal,
                    'titik_akhir'            => $r->titik_akhir,
                    'keberangkatan_pertama'  => $j['keberangkatan_pertama'] ?? null,
                    'keberangkatan_terakhir' => $j['keberangkatan_terakhir'] ?? null,
                    'jam_operasional'        => $j['jam_operasional'] ?? ($j['jam_operasional_minggu'] ?? null),
                    'headway'                => $j['headway_teks'] ?? '-',
                ];
            });
    }

    // GET /api/rute/{id} -> detail rute (dengan halte)
    public function tampil($id)
    {
        return rute::with('halte')->findOrFail($id);
    }

    // POST /api/rute
    public function tambah(Request $req)
    {
        $data = $req->validate([
            'nama_rute'   => 'required|string|min:3',
            'titik_awal'  => 'required|string|min:2',
            'titik_akhir' => 'required|string|min:2',
            'jadwal'      => 'required|array',
            'halte_daftar'=> 'sometimes|array',
            'halte_daftar.*' => 'string|min:1',
        ]);

        $r = \App\Models\rute::create($data);

        if (!empty($data['halte_daftar'])) {
            foreach (array_values($data['halte_daftar']) as $i => $nama) {
                \App\Models\halte::updateOrCreate(
                    ['rute_id'=>$r->id, 'urutan'=>$i+1],
                    ['nama_halte'=>$nama]
                );
            }
        }
        return response()->json($r->load('halte'), 201);
    }


    // PUT /api/rute/{id}
    public function ubah(Request $req, $id)
    {
        $data = $req->validate([
            'nama_rute'   => 'sometimes|required|string|min:3',
            'titik_awal'  => 'sometimes|required|string|min:2',
            'titik_akhir' => 'sometimes|required|string|min:2',
            'jadwal'      => 'sometimes|required|array',
            'halte_daftar'=> 'sometimes|array',
            'halte_daftar.*' => 'string|min:1',
        ]);

        $r = \App\Models\rute::findOrFail($id);
        $r->update($data);

        if ($req->has('halte_daftar')) {
            // reset & isi ulang sesuai urutan array
            \App\Models\halte::where('rute_id', $r->id)->delete();
            foreach (array_values($data['halte_daftar']) as $i => $nama) {
                \App\Models\halte::create([
                    'rute_id' => $r->id,
                    'nama_halte' => $nama,
                    'urutan' => $i + 1,
                ]);
            }
        }
        return response()->json($r->load('halte'));
    }

    // DELETE /api/rute/{id}
    public function hapus($id)
    {
        $obj = rute::findOrFail($id);
        $obj->delete();
        return response()->json(['pesan' => 'Data rute dihapus']);
    }
}
