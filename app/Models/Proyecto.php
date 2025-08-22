<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tecnologias',
        'imagen_url',
        'imagen_public_id',
        'demo_url',
        'github_url',
        'destacado',
        'nivel',
        'categoria_id',
    ];

    protected $casts = [
        'destacado' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categoria::class);
    }
}
