<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Asegura que Cloudinary tenga cloud_url cargado aunque la cache esté fría
        if (!config('cloudinary.cloud_url') && env('CLOUDINARY_URL')) {
            config(['cloudinary.cloud_url' => env('CLOUDINARY_URL')]);
        }
    }

    public function boot(): void
    {
        //
    }
}
