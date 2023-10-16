<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RDCReportController extends Controller
{
    use ApiResponder;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'terminal' => ['required', 'in:tpa,irge'],
            'day_date' => ['required', 'date']
        ]);

        $terminal = $request->get('terminal');
        $db = DB::connection($terminal);

        try {

            $results =$db->table('entrada')
                ->join('embarques','entrada.NoEmbarque','embarques.embarque')
                ->join('subgrupos','entrada.subgrupo','subgrupos.id')
                ->join('companias','entrada.compania','companias.id')
                ->select('entrada.noEmbarque','entrada.pg','entrada.nombrePorteador','entrada.grupo','entrada.fechaSalida','entrada.nombreDestinatario','entrada.masa','entrada.presion','embarques.finCarga_llenado')
                ->selectRaw('entrada.densidad AS densidad20')
                ->selectRaw('embarques.densidad_llenado as densidad')
                ->selectRaw('entrada.compania AS idCompania')
                ->selectRaw('companias.nombre AS compania')
                ->selectRaw('entrada.subgrupo AS idSubgrupo')
                ->selectRaw('subgrupos.nombre AS subgrupo')
                ->selectRaw('entrada.volumen AS volumen20')
                ->selectRaw('embarques.volumen_llenado AS volumen')
                ->selectRaw('entrada.fechaJornada AS fechaJ')
                ->selectRaw('FORMAT(entrada.presionTanque,1,0) AS presionTanque')
                ->selectRaw("DATE_FORMAT(entrada.fechaSalida, '%H:%i') as fechaDoc")
                ->selectRaw("FORMAT(entrada.masa, 0) AS masaStr")
                ->selectRaw("CONCAT(entrada.magnatel, '%') AS magnatel")
                ->selectRaw("ROUND((entrada.masa / entrada.densidad)) AS litros")
                ->selectRaw("DATE_FORMAT(embarques.inicioCarga_llenado, '%H:%i') as inicioCarga")
                ->selectRaw("DATE_FORMAT(embarques.finCarga_llenado, '%H:%i') as finCarga")
                ->selectRaw("IFNULL((SELECT DATE_FORMAT(fechaLlegada, '%H:%i') FROM accesos WHERE embarque = entrada.noEmbarque limit 1),'') AS fechaLlegada")
                ->where('entrada.fechaJornada', $request->get('day_date'))
                ->orderBy('entrada.id','asc')
                ->get();

            return $this->success('Consulta de información exitosa.', $results);
        } catch (\Exception $e) {
            Log::error("Error al consultar informacion, error:{$e->getMessage()}.");

            return $this->error("Error al consultar informacion, error:{$e->getMessage()}.");
        }


    }
}
