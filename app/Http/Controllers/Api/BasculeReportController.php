<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BasculeReportController extends Controller
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
        $date = $request->get('day_date');
        $db = DB::connection($terminal);

        $results = collect();

        try {

            $records =   $db->select("SELECT a.id, a.claveAcceso, a.fechaLlegada, a.fechaReporte, a.pg, a.embarque,
            a.fechaEntrada_carga, a.fechaInicio_carga, a.fechaFin_carga, a.fechaPorte, a.fechaFacturacion,
            a.idUser_reg, a.usuario_reg, a.estado, a.subgrupo, a.pesoEntrada, a.pesoSalida, a.magnatel,
            a.presion, a.programa, e.nombrePorteador as porteador, (a.pesoSalida - a.pesoEntrada) AS pesoNeto,
            e.masa AS pesoPG, emb.llenadera_llenado as llenadera, e.datosAdicionales as sellos,
            IF(a.estado = 2, (SELECT SUM(e.masa) FROM entrada e INNER JOIN accesos ac ON e.noEmbarque = ac.embarque WHERE ac.claveAcceso = a.claveAcceso), 0) AS pesoFacturado,
            s.nombre AS grupoComp
            FROM accesos a
            INNER JOIN entrada e ON a.embarque = e.noEmbarque
            INNER JOIN embarques emb ON e.noEmbarque = emb.embarque
            INNER JOIN subgrupos s ON e.subgrupo = s.id
            WHERE fechaReporte = '$date' AND a.embarque > 0
            AND a.estado < 3
            ORDER BY a.claveAcceso ASC, a.embarque ASC, a.id ASC");


            foreach ($records as $record) {
                $difMasa = 0;
                if ($record->estado == 2) {
                        $difMasa = $record->pesoNeto - $record->pesoFacturado;
                }

                $results->push([
                    'id_terminal' =>  $record->id,
                    'terminal_id' =>  null,
                    'fecha' =>  $date,
                    'claveAcceso' =>$record->claveAcceso,
                    'porteador' =>  $record->porteador,
                    'grupoComp' =>  $record->grupoComp,
                    'pg' =>  $record->pg,
                    'llenadera' =>  $record->llenadera,
                    'sellos' =>  $record->sellos,
                    'magnatel' =>  $record->magnatel,
                    'estado' =>  $record->estado,
                    'pesoEntrada' =>  $record->pesoEntrada,
                    'pesoSalida' =>  $record->pesoSalida,
                    'pesoPG' =>  $record->pesoPG,
                    'pesoNeto' =>  $record->pesoNeto,
                    'pesoFacturado' =>  $record->pesoFacturado,
                    'difMasa' =>  $difMasa,
                ]);
            }


            return $this->success('Consulta de información exitosa.', $results);
        } catch (\Exception $e) {
            Log::error("Error al consultar informacion, error:{$e->getMessage()}.");

            return $this->error("Error al consultar informacion, error:{$e->getMessage()}.");
        }
    }
}
