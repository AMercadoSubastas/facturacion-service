<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    // Determina si el usuario está autorizado para hacer esta solicitud.
    public function authorize()
    {
        return true;
    }

    // Define las reglas de validación
    public function rules()
    {
        return [
            'usuario' => 'required|string|max:15|unique:usuarios,usuario',
            'nombre' => 'required|string|max:50',
            'clave' => 'required|string|min:8|confirmed', // Confirmar contraseña
            'email' => 'required|string|email|max:60|unique:usuarios,email',
            'nivel' => 'required|integer',
        ];
    }

    // Define los mensajes personalizados para las reglas de validación
    public function messages()
    {
        return [
            'usuario.required' => 'El campo usuario es obligatorio.',
            'usuario.string' => 'El campo usuario debe ser una cadena de texto.',
            'usuario.max' => 'El campo usuario no debe superar los 15 caracteres.',
            'usuario.unique' => 'El usuario ya está registrado. Debe ser único.',

            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe superar los 50 caracteres.',

            'clave.required' => 'El campo contraseña es obligatorio.',
            'clave.string' => 'El campo contraseña debe ser una cadena de texto.',
            'clave.min' => 'El campo contraseña debe tener al menos 8 caracteres.',
            'clave.confirmed' => 'La confirmación de la contraseña no coincide.',

            'email.required' => 'El campo email es obligatorio.',
            'email.string' => 'El campo email debe ser una cadena de texto.',
            'email.email' => 'El campo email debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo email no debe superar los 60 caracteres.',
            'email.unique' => 'El email ya está registrado. Debe ser único.',

            'nivel.required' => 'El campo nivel es obligatorio.',
            'nivel.integer' => 'El campo nivel debe ser un número entero.',
        ];
    }
}
