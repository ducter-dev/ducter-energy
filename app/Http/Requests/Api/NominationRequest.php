<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class NominationRequest extends FormRequest
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
        return [
            'terminal' => ['required', 'in:tpa,irge'],
            'unidadNeg' => ['required'],
            'anio' => ['required','numeric','digits:4'],
            'mes' => ['required','numeric','digits:2'],
            'nominacion' => ['required', 'integer'],
            'createMany' => ['required', 'array'],
        ];
    }
}
