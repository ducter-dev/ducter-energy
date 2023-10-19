<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;

       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accesos';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'claveAcceso',
        'fechaLlegada',
        'fechaReporte',
        'pg',
        'embarque',
        'fechaEntrada_carga',
        'fechaInicio_carga',
        'fechaFin_carga',
        'fechaPorte',
        'fechaFacturacion',
        'idUser_reg',
        'usuario_reg',
        'estado',
        'subgrupo',
        'pesoEntrada',
        'pesoSalida',
        'magnatel',
        'presion',
        'programa',
        'numVista',
        'id_programa_energy',
    ];
}
