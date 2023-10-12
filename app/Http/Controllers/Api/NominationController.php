<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NominationRequest;
use App\Models\MonthlyNomination;
use App\Models\Nomination;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NominationController extends Controller
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

        $results = MonthlyNomination::on($terminal)->with('days')->get();

        return $this->success('Consulta de información exitosa.', $results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NominationRequest $request)
    {
        $terminal = $request->get('terminal');
        $days = $request->get('days');

        $db = DB::connection($terminal);

        $db->beginTransaction();

        try {

            $monthlyNomination = MonthlyNomination::on($terminal)->create([
                'unidadNeg' => $request->get('unidadNeg'),
                'anio'=> $request->get('anio'),
                'mes'=> $request->get('mes'),
                'nominacion'=> $request->get('nominacion'),
            ]);

            $monthlyNomination->days()->createMany($days);

            $db->commit();

            return $this->success('Registro guardado correctamente.', $monthlyNomination);
        } catch (\Exception $e) {
            Log::error("Error al guardar el registro, error:{$e->getMessage()}.");

            $db->rollBack();
            return $this->error("Error al guardar el registro, error:{$e->getMessage()}.");
        }
    }
}
