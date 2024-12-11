<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntidadRequest extends FormRequest
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
        $validate = 'required|numeric';
        return [
            'razsoc' => 'required|string|min:5|',
            'calle' => 'required|string|min:5',
            'numero' => $validate,
            'codpais' => $validate,
            'codpais' => $validate,
            'codloc' => $validate,
            'telcelu' => 'required|numeric|digits:10',
            'tipoent' => $validate,
            'tipoiva' => $validate,
            'cuit' => 'required|numeric|digits:13',
            'mailcont' => 'required|email',
            'tipoind' => 'required|numeric',
        ];
    }
}
