<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePerfilRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $in = $this->all();
        // Trimear y convertir vacío -> null
        foreach ([
            'nombre_completo','iniciales_logo','telefono','titulo_hero',
            'perfil_tecnico_hero','descripcion_hero',
            'descripcion_uno_sobre_mi','descripcion_dos_sobre_mi','email',
        ] as $f) {
            if ($this->has($f) && is_string($in[$f])) {
                $in[$f] = trim($in[$f]) === '' ? null : trim($in[$f]);
            }
        }
        $this->replace($in);
    }

    public function rules(): array
    {
        return [
            // texto
            'nombre_completo'        => ['required','string','max:255'],
            'iniciales_logo'         => ['nullable','string','max:10'],
            'telefono'               => ['nullable','string','max:50'],
            'titulo_hero'            => ['required','string','max:255'],
            'perfil_tecnico_hero'    => ['required','string','max:255'],
            'descripcion_hero'       => ['required','string'],
            'descripcion_uno_sobre_mi' => ['required','string'],
            'descripcion_dos_sobre_mi' => ['required','string'],

            // email ahora OPCIONAL
            'email'                  => ['sometimes','nullable','email','max:255', Rule::unique('perfil','email')],

            // archivos (ajusta tamaños si quieres)
            'avatar'        => ['sometimes','nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'foto_sobre_mi' => ['sometimes','nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'cv'            => ['sometimes','nullable','file','mimes:pdf','max:10240'], // 10MB
        ];
    }
}
