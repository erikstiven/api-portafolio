<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        $id = $this->route('categoria')?->id ?? $this->route('id');
        return [
            'nombre' => [
                'sometimes','string','max:100',
                Rule::unique('categorias','nombre')->ignore($id),
            ],
        ];
    }
}
