<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EquipmentRequest;
use App\Models\Equipment;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'terminal' => ['required', 'in:tpa,irge']
        ]);

        $terminal = $request->get('terminal');

        $results = Equipment::on($terminal)->get();

        return $this->success('Consulta de información exitosa.', $results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipmentRequest $request)
    {
        $terminal = $request->get('terminal');

        $db = DB::connection($terminal);
        $db->beginTransaction();

        $lastID = null;

        try {

            if ($terminal == 'tpa') {
                $lastID = Equipment::on($terminal)->orderBy('SbiID', 'desc')->first();
                $lastID = $lastID->SbiID + 1;
                logger($lastID);
            }

            $equipment = Equipment::on($terminal)->create([
                is_null($lastID) ?:     'SbiID' => $lastID,
                'pg' => $request->get('pg'),
                'grupo' => $request->get('grupo'),
                'comercializadora' => $request->get('comercializadora'),
                'porteador' => $request->get('porteador'),
                'capacidad' => $request->get('capacidad'),
                'placa' => $request->get('placa'),
                'embarque' => $request->get('embarque'),
                'fechaMod' => $request->get('fechaMod'),
                'utilizacion' => $request->get('utilizacion'),
                'idCRE' => $request->get('idCRE'),
            ]);
            $db->commit();
            return $this->success('Registro guardado correctamente.', $equipment);
        } catch (\Exception $e) {
            Log::error("Error al guardar el registro, error:{$e->getMessage()}.");
            $db->rollBack();
            return $this->error("Error al guardar el registro, error:{$e->getMessage()}.");
        }
    }
}
