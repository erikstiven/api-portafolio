<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\RedSocialController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DebugController;

// Health sin versión (útil para balanceadores legacy)
Route::get('/health', fn() => response('OK', 200));

Route::prefix('v1')->group(function () {

    // Health versionado
    Route::get('health', fn() => response('OK', 200));

    // ---- Debug (recomendado proteger o desactivar en prod)
    Route::get('debug/cloudinary', [DebugController::class, 'cloudinary']);
    Route::post('debug/upload', [DebugController::class, 'upload']);

    // ---- Auth
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('auth/logout', [AuthController::class, 'logout']);

    // ---- Recursos públicos (index/show)
    Route::apiResource('proyectos', ProyectoController::class)->only(['index', 'show']);
    Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);
    Route::apiResource('servicios',  ServicioController::class)->only(['index', 'show']);
    Route::apiResource('redes',      RedSocialController::class)->only(['index', 'show']);
    Route::apiResource('experiencias', ExperienciaController::class)->only(['index', 'show']);

    // Perfil único (siempre el primero)
    Route::get('perfil', [PerfilController::class, 'index']);
    // 👉 ya no necesitas show con {perfil}, porque siempre habrá 1
    // Route::get('perfil/{perfil}', [PerfilController::class, 'show']);

    // Descargar CV público
    Route::get('perfil/cv/download', [PerfilController::class, 'descargarCv']);

    // ---- Rutas protegidas (crear/actualizar/borrar)
    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('proyectos', ProyectoController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('categorias', CategoriaController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('servicios',  ServicioController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('redes',      RedSocialController::class)
            ->parameters(['redes' => 'redSocial'])
            ->only(['store', 'update', 'destroy']);
        Route::apiResource('experiencias', ExperienciaController::class)->only(['store', 'update', 'destroy']);

        // Perfil único (no se pasa ID, siempre trabaja con el primero)
        Route::post('perfil', [PerfilController::class, 'store']);
        Route::match(['put', 'patch'], 'perfil', [PerfilController::class, 'update']);
        Route::delete('perfil', [PerfilController::class, 'destroy']);
    });
});
