<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfil';

    protected $fillable = [
        'nombre_completo',
        'iniciales_logo',
        'telefono',
        'titulo_hero',
        'perfil_tecnico_hero',
        'descripcion_hero',
        'descripcion_uno_sobre_mi',
        'descripcion_dos_sobre_mi',
        'cv_url',
        'cv_public_id',
        'cv_filename',
        'foto_hero_url',
        'foto_hero_public_id',
        'foto_sobre_mi_url',
        'foto_sobre_mi_public_id',
    ];

    // Si no quieres exponer public_id en respuestas JSON crudas:
    protected $hidden = [
        // 'cv_public_id',
        // 'foto_hero_public_id',
        // 'foto_sobre_mi_public_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['cv_download_url'];

    // Accessor: genera URL de descarga con Content-Disposition usando Cloudinary
    public function getCvDownloadUrlAttribute(): ?string
    {
        $url = $this->cv_url ? trim($this->cv_url) : null;
        $filename = $this->cv_filename ? trim($this->cv_filename) : null;

        if (!$url || !$filename) return null;

        // Cloudinary típico: https://res.cloudinary.com/.../upload/.../resource.ext
        // Insertamos fl_attachment:<filename> justo después de "/upload/"
        $needle = '/upload/';
        $pos = strpos($url, $needle);
        if ($pos === false) return null;

        $prefix = substr($url, 0, $pos + strlen($needle));
        $suffix = substr($url, $pos + strlen($needle)); // resto (v123/..., public_id.ext?query=...)

        // Evita doble slash si suffix ya inicia con '/'
        if (isset($suffix[0]) && $suffix[0] === '/') {
            $suffix = ltrim($suffix, '/');
        }

        return $prefix . 'fl_attachment:' . rawurlencode($filename) . '/' . $suffix;
    }

    // (Opcional) Normaliza filename al asignar
    public function setCvFilenameAttribute($value): void
    {
        $this->attributes['cv_filename'] = is_string($value) ? trim($value) : $value;
    }
}
