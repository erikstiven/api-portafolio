<?php
// app/Http/Controllers/PerfilController.php
namespace App\Http\Controllers;

use App\Http\Requests\StorePerfilRequest;
use App\Http\Requests\UpdatePerfilRequest;
use App\Http\Resources\PerfilResource;
use App\Models\Perfil;
use App\Services\CloudinaryUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PerfilController extends Controller
{
    public function __construct(private CloudinaryUploader $cloud) {}

    // Si usas un único perfil, devolvemos el primero o 404
    public function index(): JsonResponse
    {
        $perfil = Perfil::query()->first();
        if (!$perfil) {
            return response()->json(['message' => 'Perfil no encontrado'], 404);
        }
        return response()->json(new PerfilResource($perfil), 200);
    }

    public function store(StorePerfilRequest $request): JsonResponse
    {
        $perfil = Perfil::create($request->validated());

        // Avatar (foto hero)
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $name = pathinfo($request->file('avatar')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadImage(
                $request->file('avatar')->getRealPath(),
                'perfiles/avatar',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $perfil->update([
                'foto_hero_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'foto_hero_public_id' => $up['public_id'] ?? null,
            ]);
        }

        // Foto "sobre mí"
        if ($request->hasFile('foto_sobre_mi') && $request->file('foto_sobre_mi')->isValid()) {
            $name = pathinfo($request->file('foto_sobre_mi')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadImage(
                $request->file('foto_sobre_mi')->getRealPath(),
                'perfiles/sobre-mi',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $perfil->update([
                'foto_sobre_mi_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'foto_sobre_mi_public_id' => $up['public_id'] ?? null,
            ]);
        }

        // CV (PDF, raw)
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            $name = pathinfo($request->file('cv')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadRaw(
                $request->file('cv')->getRealPath(),
                'perfiles/cv',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'format'            => 'pdf',
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $perfil->update([
                'cv_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'cv_public_id' => $up['public_id'] ?? null,
                'cv_filename'  => $request->file('cv')->getClientOriginalName(),
            ]);
        }

        return response()->json(new PerfilResource($perfil->fresh()), 201);
    }

    public function show(Perfil $perfil): PerfilResource
    {
        return new PerfilResource($perfil);
    }

    public function update(UpdatePerfilRequest $request, Perfil $perfil): JsonResponse
    {
        // Campos de texto
        $perfil->update($request->validated());

        // Borrar archivos si se pidió
        if ($request->boolean('remove_avatar') && $perfil->foto_hero_public_id) {
            $this->cloud->destroy($perfil->foto_hero_public_id, 'image');
            $perfil->update(['foto_hero_url' => null, 'foto_hero_public_id' => null]);
        }
        if ($request->boolean('remove_foto_sobre_mi') && $perfil->foto_sobre_mi_public_id) {
            $this->cloud->destroy($perfil->foto_sobre_mi_public_id, 'image');
            $perfil->update(['foto_sobre_mi_url' => null, 'foto_sobre_mi_public_id' => null]);
        }
        if ($request->boolean('remove_cv') && $perfil->cv_public_id) {
            $this->cloud->destroy($perfil->cv_public_id, 'raw');
            $perfil->update(['cv_url' => null, 'cv_public_id' => null, 'cv_filename' => null]);
        }

        // Reemplazar avatar
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($perfil->foto_hero_public_id) {
                $this->cloud->destroy($perfil->foto_hero_public_id, 'image');
            }
            $name = pathinfo($request->file('avatar')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadImage(
                $request->file('avatar')->getRealPath(),
                'perfiles/avatar',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $perfil->update([
                'foto_hero_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'foto_hero_public_id' => $up['public_id'] ?? null,
            ]);
        }

        // Reemplazar "sobre mí"
        if ($request->hasFile('foto_sobre_mi') && $request->file('foto_sobre_mi')->isValid()) {
            if ($perfil->foto_sobre_mi_public_id) {
                $this->cloud->destroy($perfil->foto_sobre_mi_public_id, 'image');
            }
            $name = pathinfo($request->file('foto_sobre_mi')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadImage(
                $request->file('foto_sobre_mi')->getRealPath(),
                'perfiles/sobre-mi',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $perfil->update([
                'foto_sobre_mi_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'foto_sobre_mi_public_id' => $up['public_id'] ?? null,
            ]);
        }

        // Reemplazar CV
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            if ($perfil->cv_public_id) {
                $this->cloud->destroy($perfil->cv_public_id, 'raw');
            }
            $name = pathinfo($request->file('cv')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadRaw(
                $request->file('cv')->getRealPath(),
                'perfiles/cv',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'format'            => 'pdf',
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $perfil->update([
                'cv_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'cv_public_id' => $up['public_id'] ?? null,
                'cv_filename'  => $request->file('cv')->getClientOriginalName(),
            ]);
        }

        return response()->json(new PerfilResource($perfil->fresh()), 200);
    }

    public function destroy(Perfil $perfil): JsonResponse
    {
        if ($perfil->foto_hero_public_id) {
            $this->cloud->destroy($perfil->foto_hero_public_id, 'image');
        }
        if ($perfil->foto_sobre_mi_public_id) {
            $this->cloud->destroy($perfil->foto_sobre_mi_public_id, 'image');
        }
        if ($perfil->cv_public_id) {
            $this->cloud->destroy($perfil->cv_public_id, 'raw');
        }
        $perfil->delete();
        return response()->json(null, 204);
    }
}
