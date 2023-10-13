<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AccessRequest;
use App\Models\Access;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccessController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'terminal' => ['required', 'in:tpa,irge'],
            'por_campo' => ['required'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_final' => ['required', 'date'],
            'pg' => ['required'],
        ]);

        $terminal = $request->get('terminal');

        $results = Access::on($terminal)
        ->where($request->get('por_campo'),'>=',$request->get('fecha_inicio'))
        ->where($request->get('por_campo'),'<=',$request->get('fecha_final'))
        ->where('pg',$request->get('pg'))
        ->first();

        return $this->success('Consulta de información exitosa.', $results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(AccessRequest $request, $id)
    {
        $terminal = $request->get('terminal');

        $db = DB::connection($terminal);
        $db->beginTransaction();

        try {

            $access = Access::on( $terminal )->findOrFail($id);

            $access->update([
                'programa' => 1,
                'subgrupo' => $request->get('subgrupo')
            ]);

            $db->commit();

            $access->refresh();
            return $this->success('Registro actualizado correctamente.', $access);
        } catch (\Exception $e) {
            Log::error("Error al actualizar el registro, error:{$e->getMessage()}.");
            $db->rollBack();
            return $this->error("Error al actualizar el registro, error:{$e->getMessage()}.");
        }
    }
}
