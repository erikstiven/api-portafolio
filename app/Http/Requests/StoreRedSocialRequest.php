<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedSocialRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activo' => filter_var($this->activo, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE),
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required','string','max:100'],
            'url'    => ['required','url','max:2048'],
            'icono'  => ['required','string','max:100'],
            'activo' => ['nullable','boolean'],
        ];
    }
}
