<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExperienciaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'puesto'      => $this->puesto,
            'empresa'     => $this->empresa,
            'fechaInicio' => $this->fecha_inicio,
            'fechaFin'    => $this->fecha_fin,
            'actualmente' => (bool)$this->actualmente,
            'descripcion' => $this->descripcion,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at,
        ];
    }
}
