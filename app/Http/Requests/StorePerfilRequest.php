<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerfilRequest extends FormRequest
{
    /**
     * Autoriza esta request (ajusta si usas policies/guards).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normaliza datos ANTES de validar:
     * - trim a strings
     * - convierte cadenas vacías en null (compatibles con "nullable")
     * - normaliza teléfono (quita espacios duplicados)
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        // Campos de texto a recortar
        $textFields = [
            'nombre_completo','iniciales_logo','telefono','titulo_hero',
            'perfil_tecnico_hero','descripcion_hero',
            'descripcion_uno_sobre_mi','descripcion_dos_sobre_mi',
        ];

        foreach ($textFields as $field) {
            if ($this->has($field)) {
                $val = is_string($input[$field]) ? trim($input[$field]) : $input[$field];
                // Convierte "" en null para que pase "nullable"
                $input[$field] = ($val === '') ? null : $val;
            }
        }

        // Normalización ligera del teléfono (sin formatear agresivo)
        if (!empty($input['telefono']) && is_string($input['telefono'])) {
            // Colapsa espacios múltiples y quita espacios a los lados
            $input['telefono'] = preg_replace('/\s+/', ' ', trim($input['telefono']));
        }

        $this->replace($input);
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            // "bail" corta en el primer error de la regla (mensajes más claros)
            'nombre_completo'           => ['bail','required','string','max:255'],
            'iniciales_logo'            => ['nullable','string','max:10'],
            // Teléfono flexible: dígitos, +, espacios, guiones, paréntesis; mínimo 6 símbolos útiles
            'telefono'                  => ['nullable','string','max:20','regex:/^[0-9+\-\s()]{6,}$/'],
            'titulo_hero'               => ['nullable','string','max:255'],
            'perfil_tecnico_hero'       => ['nullable','string','max:255'],
            'descripcion_hero'          => ['nullable','string'],
            'descripcion_uno_sobre_mi'  => ['nullable','string'],
            'descripcion_dos_sobre_mi'  => ['nullable','string'],

            // Archivos:
            'foto_sobre_mi' => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:5120'],
            // avatar: imagen (jpg/jpeg/png/webp) hasta 5MB
            'avatar'                    => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:5120'],
            // cv: PDF hasta 10MB
            'cv'                        => ['nullable','file','mimes:pdf','max:10240'],
        ];
    }

    /**
     * Mensajes en español (más amigables para el frontend).
     */
    public function messages(): array
    {
        return [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'telefono.regex'           => 'El teléfono solo puede contener dígitos, espacios, +, -, y ().',
            'avatar.image'             => 'El avatar debe ser una imagen válida.',
            'avatar.mimes'             => 'El avatar debe ser JPG, JPEG, PNG o WEBP.',
            'avatar.max'               => 'El avatar no puede superar 5 MB.',
            'cv.mimes'                 => 'El CV debe ser un archivo PDF.',
            'cv.max'                   => 'El CV no puede superar 10 MB.',
        ];
    }

    /**
     * Alias “bonitos” para los campos en los mensajes.
     */
    public function attributes(): array
    {
        return [
            'nombre_completo'          => 'nombre completo',
            'iniciales_logo'           => 'iniciales del logo',
            'telefono'                 => 'teléfono',
            'titulo_hero'              => 'título destacado',
            'perfil_tecnico_hero'      => 'perfil técnico',
            'descripcion_hero'         => 'descripción destacada',
            'descripcion_uno_sobre_mi' => 'descripción (sobre mí 1)',
            'descripcion_dos_sobre_mi' => 'descripción (sobre mí 2)',
            'avatar'                   => 'foto de perfil',
            'cv'                       => 'currículum (PDF)',
        ];
    }
}
