<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $connection = request('terminal','tpa');

        return [
            'terminal' => ['required', 'in:tpa,irge'],
            'pg' => ['required', 'max:30',"unique:{$connection}.autotanques,pg" ],
            'grupo' => ['nullable'],
            'comercializadora' => ['nullable'],
            'porteador' => ['nullable'],
            'capacidad' => ['required', 'integer'],
            'placa' => ['required', 'max:30',"unique:{$connection}.autotanques,placa" ],
            'embarque' => ['nullable'],
            'fechaMod' => ['required', 'date'],
            'utilizacion' => ['nullable'],
            'idCRE' => ['required',"unique:{$connection}.autotanques,idCRE" ],
        ];
    }

     /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'terminal' => 'Terminal',
            'pg' => 'PG',
            'grupo' => 'Grupo',
            'comercializadora' => 'Comercializadora',
            'porteador' => 'Porteador',
            'capacidad' => 'Capacidad',
            'placa' => 'Placa',
            'embarque' => 'Embarque',
            'fechaMod' => 'Fecha de modificacion',
            'utilizacion' => 'Utilizacion',
            'idCRE' => 'ID CRE',
        ];
    }
}
