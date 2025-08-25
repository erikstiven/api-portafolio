<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRedSocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation(): void
    {
        if ($this->has('activo')) {
            $this->merge([
                'activo' => filter_var($this->activo, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }


    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'string', 'max:100'],
            'url'    => ['sometimes', 'url', 'max:2048'],
            'icono'  => ['sometimes', 'string', 'max:100'],
            'activo' => ['sometimes', 'nullable', 'boolean'],
        ];
    }
}
