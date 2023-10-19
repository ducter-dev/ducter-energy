<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccessRequest extends FormRequest
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
            'pg' => ['required'],
            'user_id' => ['required'],
            'user' => ['required'],
            'subgroup' => ['required'],
            'program' => ['required'],
            'program_id' => ['required'],
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
            'subgroup' => 'Subgrupo',
            'pg' => 'PG',
            'user_id' => 'ID de usuario',
            'user' => 'Usuario',
            'program' => 'Programa',
            'program_id' => 'ID de programa',
        ];
    }
}
