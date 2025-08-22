<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RedSocial extends Model
{
    use HasFactory;

    protected $table = 'redes_sociales';
    protected $fillable = ['nombre', 'url', 'icono', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
