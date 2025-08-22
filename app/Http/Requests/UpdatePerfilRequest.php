<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfilRequest extends FormRequest
{
    /**
     * Autoriza esta request (ajusta si usas policies).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normaliza datos ANTES de validar:
     * - trim a strings
     * - "" -> null (compatibles con "nullable")
     * - colapsa espacios en teléfono
     */
    protected function prepareForValidation(): void
    {
        $input = $this->all();

        $textFields = [
            'nombre_completo',
            'iniciales_logo',
            'telefono',
            'titulo_hero',
            'perfil_tecnico_hero',
            'descripcion_hero',
            'descripcion_uno_sobre_mi',
            'descripcion_dos_sobre_mi',
        ];

        foreach ($textFields as $field) {
            if ($this->has($field)) {
                $val = is_string($input[$field]) ? trim($input[$field]) : $input[$field];
                $input[$field] = ($val === '') ? null : $val;
            }
        }

        if (!empty($input['telefono']) && is_string($input['telefono'])) {
            $input['telefono'] = preg_replace('/\s+/', ' ', trim($input['telefono']));
        }

        // Flags para eliminar archivos existentes (opcionales)
        // Front puede enviar remove_avatar=true / remove_cv=true
        if ($this->has('remove_avatar')) {
            $input['remove_avatar'] = filter_var($input['remove_avatar'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        }
        if ($this->has('remove_cv')) {
            $input['remove_cv'] = filter_var($input['remove_cv'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        }

        $this->replace($input);
    }

    /**
     * Reglas de validación.
     * "sometimes|nullable": valida solo si el campo viene y permite null.
     */
    public function rules(): array
    {
        return [
            'nombre_completo'           => ['bail', 'sometimes', 'nullable', 'string', 'max:255'],
            'iniciales_logo'            => ['sometimes', 'nullable', 'string', 'max:10'],
            'telefono'                  => ['sometimes', 'nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{6,}$/'],
            'titulo_hero'               => ['sometimes', 'nullable', 'string', 'max:255'],
            'perfil_tecnico_hero'       => ['sometimes', 'nullable', 'string', 'max:255'],
            'descripcion_hero'          => ['sometimes', 'nullable', 'string'],
            'descripcion_uno_sobre_mi'  => ['sometimes', 'nullable', 'string'],
            'descripcion_dos_sobre_mi'  => ['sometimes', 'nullable', 'string'],

            // Archivos (opcionales):
            'avatar'                    => ['sometimes', 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cv'                        => ['sometimes', 'nullable', 'file', 'mimes:pdf', 'max:10240'],
            'foto_sobre_mi'        => ['sometimes', 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            // Flags opcionales para borrar archivos actuales
            'remove_avatar'             => ['sometimes', 'boolean'],
            'remove_cv'                 => ['sometimes', 'boolean'],
            'remove_foto_sobre_mi' => ['sometimes','boolean'],

        ];
    }

    /**
     * Mensajes en español.
     */
    public function messages(): array
    {
        return [
            'telefono.regex'   => 'El teléfono solo puede contener dígitos, espacios, +, -, y ().',
            'avatar.image'     => 'El avatar debe ser una imagen válida.',
            'avatar.mimes'     => 'El avatar debe ser JPG, JPEG, PNG o WEBP.',
            'avatar.max'       => 'El avatar no puede superar 5 MB.',
            'cv.mimes'         => 'El CV debe ser un archivo PDF.',
            'cv.max'           => 'El CV no puede superar 10 MB.',
        ];
    }

    /**
     * Alias para los campos.
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
            'remove_avatar'            => 'eliminar avatar',
            'remove_cv'                => 'eliminar CV',
        ];
    }
}
