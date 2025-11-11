<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rute;   
use App\Models\halte;  


class HalteController extends Controller
{
        // GET /halte
    public function index() { return Halte::with('rute')->orderBy('rute_id')->orderBy('urutan')->get(); }

    // GET /halte/{id}
    public function show($id) { return Halte::with('rute')->findOrFail($id); }

    // PUT /halte/{id}
    public function update(Request $req, $id) {
        $h = Halte::findOrFail($id);
        $h->update($req->validate([
            'nama_halte' => 'required|string',
            'urutan' => 'integer|min:1',
        ]));
        return $h;
    }

    // DELETE /halte/{id}
    public function destroy($id) {
        Halte::findOrFail($id)->delete();
        return response()->json(['message'=>'deleted']);
    }

}
