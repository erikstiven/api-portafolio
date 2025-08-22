<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'nombre'      => ['required','string','max:150'],
            'descripcion' => ['required','string'],
            'precio'      => ['nullable','numeric','min:0','max:999999.99'],
        ];
    }
}
