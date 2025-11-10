<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rute;   
use App\Models\halte;  

class RuteController extends Controller
{
    // GET /api/rute/{rute_id}/halte -> daftar halte berurutan
    public function daftar($rute_id)
    {
        $r = rute::findOrFail($rute_id);
        return $r->halte()->orderBy('urutan')->get();
    }

    // POST /api/rute/{rute_id}/halte -> tambah halte untuk rute tertentu
    public function tambah(Request $req, $rute_id)
    {
        $data = $req->validate([
            'nama_halte' => 'required|string|min:2',
            'urutan'     => 'required|integer|min:1',
        ]);

        $r = rute::findOrFail($rute_id);
        // unik (rute_id, urutan) sudah dijaga di migrasi dengan unique index
        $h = $r->halte()->create($data);
        return response()->json($h, 201);
    }

    // PUT /api/halte/{id} -> ubah halte
    public function ubah(Request $req, $id)
    {
        $data = $req->validate([
            'nama_halte' => 'sometimes|required|string|min:2',
            'urutan'     => 'sometimes|required|integer|min:1',
        ]);

        $h = halte::findOrFail($id);
        $h->update($data);
        return response()->json($h);
    }

    // DELETE /api/halte/{id} -> hapus halte
    public function hapus($id)
    {
        $h = halte::findOrFail($id);
        $h->delete();
        return response()->json(['pesan' => 'Data halte dihapus']);
    }
}
