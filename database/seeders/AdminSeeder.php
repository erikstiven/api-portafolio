<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = config('admin.email');
        $name     = config('admin.name', 'Admin');
        $password = config('admin.password');

        if (!$email || !$password) {
            // Evita crear un usuario con datos vacíos
            throw new \RuntimeException('Faltan ADMIN_EMAIL o ADMIN_PASSWORD en el .env / config.');
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password)]
        );
    }
}
