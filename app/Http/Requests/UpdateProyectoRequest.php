<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProyectoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $in = $this->all();
        foreach (['titulo','descripcion','tecnologias','demo_url','github_url','nivel'] as $f) {
            if ($this->has($f) && is_string($in[$f])) {
                $in[$f] = trim($in[$f]) === '' ? null : trim($in[$f]);
            }
        }
        $this->replace($in);
    }

    public function rules(): array
    {
        return [
            'titulo'       => ['sometimes','string','max:255'],
            'descripcion'  => ['sometimes','string'],
            'tecnologias'  => ['sometimes','string','max:255'],
            'categoria_id' => ['sometimes','integer','exists:categorias,id'],

            'demo_url'     => ['sometimes','nullable','url','max:255'],
            'github_url'   => ['sometimes','nullable','url','max:255'],
            'destacado'    => ['sometimes','boolean'],
            'nivel'        => ['sometimes','nullable','string','max:50'],

            'imagen'        => ['sometimes','nullable','file','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'remove_imagen' => ['sometimes','boolean'],
        ];
    }
}
