<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AccessRequest;
use App\Http\Requests\Api\StoreAccessRequest;
use App\Models\Access;
use App\Traits\ApiResponder;
use Carbon\Carbon;
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
            ->where($request->get('por_campo'), '>=', $request->get('fecha_inicio'))
            ->where($request->get('por_campo'), '<=', $request->get('fecha_final'))
            ->where('pg', $request->get('pg'))
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

            $access = Access::on($terminal)->findOrFail($id);

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


    /**
     * Store a newly created resource in storage.
     */
    public function create(StoreAccessRequest $request)
    {
        $terminal = $request->get('terminal');

        $db = DB::connection($terminal);
        $db->beginTransaction();

        $tz = 'America/Mexico_City';

        $fechaReporte = Carbon::now($tz)->format('Y-m-d');

        try {

            $now = Carbon::now($tz)->format('H:i:s');

            if ($now < '05:00:00') {
                $fechaReporte = Carbon::now($tz)->subDay()->format('Y-m-d');
            }

            $access = Access::on($terminal)->create([
                'claveAcceso' => now($tz)->format('ymdHis'),
                'fechaLlegada' => now($tz)->format('Y-m-d H:i:s'),
                'embarque' => 0,
                'estado' => 1,
                'presion' => 0,
                'fechaReporte' => $fechaReporte,
                'pg' => $request->get('pg'),
                'idUser_reg'  => $request->get('user_id'),
                'usuario_reg'  => $request->get('user'),
                'subgrupo'  => $request->get('subgroup'),
                'programa'  => $request->get('program'),
                'id_programa_energy'  => $request->get('program_id'),
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
