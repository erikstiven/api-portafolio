<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienciaRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation(): void
    {
        $this->merge([
            'actualmente' => filter_var($this->actualmente, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE),
        ]);
    }

    public function rules(): array
    {
        return [
            'puesto'       => ['required','string','max:150'],
            'empresa'      => ['required','string','max:150'],
            'fecha_inicio' => ['required','date'],
            'fecha_fin'    => ['nullable','date','after:fecha_inicio','prohibited_if:actualmente,true'],
            'actualmente'  => ['nullable','boolean'],
            'descripcion'  => ['required','string'],
        ];
    }
}
