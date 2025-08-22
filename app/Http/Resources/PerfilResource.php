<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerfilResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->id,
            'nombreCompleto'         => $this->nombre_completo,
            'inicialesLogo'          => $this->iniciales_logo,
            'telefono'               => $this->telefono,
            'tituloHero'             => $this->titulo_hero,
            'perfilTecnicoHero'      => $this->perfil_tecnico_hero,
            'descripcionHero'        => $this->descripcion_hero,
            'descripcionUnoSobreMi'  => $this->descripcion_uno_sobre_mi,
            'descripcionDosSobreMi'  => $this->descripcion_dos_sobre_mi,

            // Archivos / Cloudinary
            'cvUrl'                  => $this->cv_url,
            'cvDownloadUrl'          => $this->cv_download_url, // ← accessor del modelo
            'cvPublicId'             => $this->cv_public_id,

            'fotoHeroUrl'            => $this->foto_hero_url,
            'fotoHeroPublicId'       => $this->foto_hero_public_id,
            'fotoSobreMiUrl'         => $this->foto_sobre_mi_url,
            'fotoSobreMiPublicId'    => $this->foto_sobre_mi_public_id,

            // Fechas en formato ISO 8601
            'createdAt'              => optional($this->created_at)->toIso8601String(),
            'updatedAt'              => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
