<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicioRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'nombre'      => ['sometimes','string','max:150'],
            'descripcion' => ['sometimes','string'],
            'precio'      => ['sometimes','nullable','numeric','min:0','max:999999.99'],
        ];
    }
}
