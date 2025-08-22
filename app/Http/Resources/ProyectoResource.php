<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'titulo'       => $this->titulo,
            'descripcion'  => $this->descripcion,
            'tecnologias'  => $this->tecnologias, // <- string tal cual (p.ej. "Laravel, Vue, MySQL")
            'tecnologiasArray' => $this->tecnologias ? array_map('trim', explode(',', $this->tecnologias)) : [], // <- opcional

            'imagenUrl'    => $this->imagen_url, // <- sin imagenPublicId (no se expone)
            'demoUrl'      => $this->demo_url,
            'githubUrl'    => $this->github_url,
            'destacado'    => (bool) $this->destacado,
            'nivel'        => $this->nivel,
            'categoriaId'  => $this->categoria_id,

            'createdAt'    => optional($this->created_at)->toIso8601String(),
            'updatedAt'    => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
