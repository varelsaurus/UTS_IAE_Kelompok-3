<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;   
use App\Models\halte;  
use OpenApi\Annotations as OA;

class HalteController extends Controller
{
    /**
 * @OA\Get(
 *   path="/api/halte",
 *   tags={"Halte"},
 *   summary="Daftar halte (bisa termasuk relasi rute)",
 *   @OA\Response(response=200, description="OK",
 *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Halte"))
 *   )
 * )
 */

    public function index() { return Halte::with('rute')->orderBy('rute_id')->orderBy('urutan')->get(); }

    /**
 * @OA\Get(
 *   path="/api/halte/{id}",
 *   tags={"Halte"},
 *   summary="Detail halte berdasarkan ID (termasuk relasi rute)",
 *   @OA\Parameter(ref="#/components/parameters/Id"),
 *   @OA\Response(response=200, description="OK",
 *     @OA\JsonContent(ref="#/components/schemas/Halte")
 *   ),
 *   @OA\Response(response=404, description="Not Found")
 * )
 */
    public function show($id) { return Halte::with('rute')->findOrFail($id); }

    /**
 * @OA\Patch(
 *   path="/api/halte/{id}",
 *   tags={"Halte"},
 *   summary="Ubah sebagian data halte",
 *   @OA\Parameter(ref="#/components/parameters/Id"),
 *   @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/HalteUpdate")),
 *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Halte")),
 *   @OA\Response(response=400, description="Validation error"),
 *   @OA\Response(response=404, description="Not Found")
 * )
 */

    public function update(Request $req, $id) {
        $h = Halte::findOrFail($id);
        $h->update($req->validate([
            'nama_halte' => 'required|string',
            'urutan' => 'integer|min:1',
        ]));
        return $h;
    }

    /**
 * @OA\Delete(
 *   path="/api/halte/{id}",
 *   tags={"Halte"},
 *   summary="Hapus halte",
 *   @OA\Parameter(ref="#/components/parameters/Id"),
 *   @OA\Response(
 *     response=200,
 *     description="Deleted",
 *     @OA\JsonContent(type="object",
 *       @OA\Property(property="message", type="string", example="deleted")
 *     )
 *   ),
 *   @OA\Response(response=404, description="Not Found")
 * )
 */

    public function destroy($id) {
        Halte::findOrFail($id)->delete();
        return response()->json(['message'=>'deleted']);
    }

}
