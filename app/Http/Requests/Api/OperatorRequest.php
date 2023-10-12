<?php

namespace App\Http\Requests\Api;

use App\Models\Operator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OperatorRequest extends FormRequest
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
            'nombre_operador' => ['required',"unique:{$connection}.operador,nombre_operador" ],
            'grupo' => ['required_if:terminal,tpa'],
            'telefonoOperador' => ['nullable','size:10', 'regex:/^(\d{1,2}){9,10}$/i',],
            'identificacion' => ['required'],
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
            'nombre_operador' => 'Nombre operador',
            'grupo' => 'Grupo',
            'telefonoOperador' => 'Telefono operador',
            'identificacion' => 'Identificacion'
        ];
    }
}
