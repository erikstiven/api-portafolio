<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyectoRequest extends FormRequest
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
            'titulo'       => ['bail','required','string','max:255'],
            'descripcion'  => ['bail','required','string'],
            'tecnologias'  => ['bail','required','string','max:255'],
            'categoria_id' => ['bail','required','integer','exists:categorias,id'],

            'demo_url'     => ['nullable','url','max:255'],
            'github_url'   => ['nullable','url','max:255'],
            'destacado'    => ['nullable','boolean'],
            'nivel'        => ['nullable','string','max:50'],

            // Imagen a Cloudinary
            'imagen'       => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tecnologias'  => 'tecnologías',
            'demo_url'     => 'URL de demo',
            'github_url'   => 'URL de GitHub',
            'categoria_id' => 'categoría',
            'imagen'       => 'imagen',
        ];
    }
}
