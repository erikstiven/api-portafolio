<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio'      => $this->precio,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at,
        ];
    }
}
