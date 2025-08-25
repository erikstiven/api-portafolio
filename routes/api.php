<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, ProyectoController, CategoriaController, ServicioController,
    RedSocialController, ExperienciaController, PerfilController, DebugController
};

// /api/health sin versión
Route::get('/health', fn() => response('OK', 200));

Route::prefix('v1')->group(function () {

    // /api/v1/health
    Route::get('health', fn() => response('OK', 200));

    // ---- Auth (JWT)
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);
    });

    // ---- Debug (mejor protegerlo; opcional)
    // Route::middleware('auth:api')->group(function () {
    //     Route::get('debug/cloudinary', [DebugController::class, 'cloudinary']);
    //     Route::post('debug/upload',    [DebugController::class, 'upload']);
    // });

    // ---- Públicos (solo lectura)
    Route::apiResource('proyectos', ProyectoController::class)->only(['index','show']);
    Route::apiResource('categorias', CategoriaController::class)->only(['index','show']);
    Route::apiResource('servicios',  ServicioController::class)->only(['index','show']);
    Route::apiResource('redes',      RedSocialController::class)
        ->parameters(['redes' => 'redSocial'])
        ->only(['index','show']);
    Route::apiResource('experiencias', ExperienciaController::class)->only(['index','show']);

    // Perfil único (lectura pública + descarga CV)
    Route::get('perfil', [PerfilController::class, 'index']);
    Route::get('perfil/cv/download', [PerfilController::class, 'descargarCv']);

    // ---- Protegidos (crear/editar/borrar)
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('proyectos', ProyectoController::class)->only(['store','update','destroy']);
        Route::apiResource('categorias', CategoriaController::class)->only(['store','update','destroy']);
        Route::apiResource('servicios',  ServicioController::class)->only(['store','update','destroy']);
        Route::apiResource('redes',      RedSocialController::class)
            ->parameters(['redes' => 'redSocial'])
            ->only(['store','update','destroy']);
        Route::apiResource('experiencias', ExperienciaController::class)->only(['store','update','destroy']);

        // Perfil único (sin ID)
        Route::post('perfil', [PerfilController::class, 'store']);
        Route::match(['put','patch'], 'perfil', [PerfilController::class, 'update']);
        Route::delete('perfil', [PerfilController::class, 'destroy']);
    });
});
