<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;   // ← AÑADE ESTO
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;    // ← AÑADE HasApiTokens

    protected $fillable = ['name','email','password'];

    protected $hidden = ['password','remember_token'];
}
