<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rute;   
use App\Models\halte;  


class HalteController extends Controller
{
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
                    'keberangkatan_pertama'  => $j['keberangkatan_pertama'] ?? ($j['jam_operasional'] ?? null),
                    'keberangkatan_terakhir' => $j['keberangkatan_terakhir'] ?? null,
                    'jam_operasional'        => $j['jam_operasional'] ?? ($j['jam_operasional_minggu'] ?? null),
                    'headway'                => $head ? (($head['min'] ?? '').'–'.($head['max'] ?? '').' menit sekali') : null,
                ];
            });
    }

    // GET /api/rute/{id} -> detail rute (dengan halte)
    public function tampil($id)
    {
        return rute::with('halte')->findOrFail($id);
    }

    // POST /api/rute -> tambah rute
    public function tambah(Request $req)
    {
        $data = $req->validate([
            'nama_rute'   => 'required|string|min:3',
            'titik_awal'  => 'required|string|min:2',
            'titik_akhir' => 'required|string|min:2',
            'jadwal'      => 'required|array',
        ]);

        $obj = rute::create($data);
        return response()->json($obj, 201);
    }

    // PUT /api/rute/{id} -> ubah rute
    public function ubah(Request $req, $id)
    {
        $data = $req->validate([
            'nama_rute'   => 'sometimes|required|string|min:3',
            'titik_awal'  => 'sometimes|required|string|min:2',
            'titik_akhir' => 'sometimes|required|string|min:2',
            'jadwal'      => 'sometimes|required|array',
        ]);

        $obj = rute::findOrFail($id);
        $obj->update($data);
        return response()->json($obj);
    }

    // DELETE /api/rute/{id} -> hapus rute
    public function hapus($id)
    {
        $obj = rute::findOrFail($id);
        $obj->delete();
        return response()->json(['pesan' => 'Data rute dihapus']);
    }
}
