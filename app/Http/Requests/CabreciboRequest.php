<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabreciboRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validate = 'required|numeric';
        return [
            'tcomp' => $validate,
            'serie' => $validate,
            'ncomp' => $validate,
            'cliente' => $validate,
            'codrem' => $validate,
            'totbruto' => $validate,
            'totneto' => $validate,
            'totimp' => $validate,
            'tipoiva' => $validate,
            'nrengs' => $validate,
            'usuario' => $validate,
            'concepto' => $validate,
            'CAE' => $validate,
            'CAEFchVto' => 'required'

        ];
    }
}
