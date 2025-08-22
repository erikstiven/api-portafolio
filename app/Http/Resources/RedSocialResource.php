<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RedSocialResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'      => $this->id,
            'nombre'  => $this->nombre,
            'url'     => $this->url,
            'icono'   => $this->icono,
            'activo'  => (bool)$this->activo,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
