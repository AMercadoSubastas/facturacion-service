<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BancoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // hacer permisos de usuario // todavia no tengo el sistema de autenticacion
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
            'codbanco' => 'required|numeric|digits:5',
            'nombre' => 'required|string|max:60',
            'codpais' => 'required|numeric',
        ];
        
    }

    public function messages()
    {
        return [
            'codbanco.digits' => 'El campo Código del Banco es de 5 digitos',
        ];
    }
}
