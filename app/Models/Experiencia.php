<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experiencia extends Model
{
    use HasFactory;

    protected $table = 'experiencias';
    protected $fillable = [
        'puesto', 'empresa', 'fecha_inicio', 'fecha_fin',
        'actualmente', 'descripcion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'actualmente'  => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];
}
