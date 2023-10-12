<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OperatorRequest;
use App\Models\Operator;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperatorController extends Controller
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

        $results = Operator::on($terminal)->get();

        return $this->success('Consulta de información exitosa.', $results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperatorRequest $request)
    {
        $terminal = $request->get('terminal');
        $db = DB::connection($terminal);
        $db->beginTransaction();

        try {
            $monthlyNomination = Operator::on($terminal)->create([
                'id_operador' => round(microtime(true) * 1000),
                'nombre_operador' => $request->get('nombre_operador'),
                'grupo' => $request->get('grupo'),
                'telefonoOperador' => $request->get('telefonoOperador'),
                'identificacion'  => $request->get('identificacion'),
            ]);
            $db->commit();
            return $this->success('Registro guardado correctamente.', $monthlyNomination);
        } catch (\Exception $e) {
            Log::error("Error al guardar el registro, error:{$e->getMessage()}.");
            $db->rollBack();
            return $this->error("Error al guardar el registro, error:{$e->getMessage()}.");
        }
    }
}
