<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePerfilRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $in = $this->all();
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
        $perfil = $this->route('perfil'); // route model binding

        return [
            'nombre_completo'        => ['sometimes','string','max:255'],
            'iniciales_logo'         => ['sometimes','nullable','string','max:10'],
            'telefono'               => ['sometimes','nullable','string','max:50'],
            'titulo_hero'            => ['sometimes','string','max:255'],
            'perfil_tecnico_hero'    => ['sometimes','string','max:255'],
            'descripcion_hero'       => ['sometimes','string'],
            'descripcion_uno_sobre_mi' => ['sometimes','string'],
            'descripcion_dos_sobre_mi' => ['sometimes','string'],

            'email' => ['sometimes','nullable','email','max:255',
                Rule::unique('perfil','email')->ignore(optional($perfil)->id)
            ],

            'remove_avatar'        => ['sometimes','boolean'],
            'remove_foto_sobre_mi' => ['sometimes','boolean'],
            'remove_cv'            => ['sometimes','boolean'],

            'avatar'        => ['sometimes','nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'foto_sobre_mi' => ['sometimes','nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'cv'            => ['sometimes','nullable','file','mimes:pdf','max:10240'],
        ];
    }
}
