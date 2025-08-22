<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienciaRequest extends FormRequest
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
            'puesto'       => ['sometimes','string','max:150'],
            'empresa'      => ['sometimes','string','max:150'],
            'fecha_inicio' => ['sometimes','date'],
            'fecha_fin'    => ['sometimes','nullable','date','after:fecha_inicio','prohibited_if:actualmente,true'],
            'actualmente'  => ['sometimes','nullable','boolean'],
            'descripcion'  => ['sometimes','string'],
        ];
    }
}
