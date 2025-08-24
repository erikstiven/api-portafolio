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

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                    'resource_type'     => 'raw',   
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'format'            => 'pdf',
                    'unique_filename'   => false,
                    'overwrite'         => true,
                    'access_mode'       => 'public', 
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

    // Opcional: show devuelve el primer perfil (no depende de route-model binding)
    public function show(): JsonResponse
    {
        $perfil = Perfil::first();
        if (! $perfil) {
            return response()->json(['message' => 'Perfil no encontrado'], 404);
        }
        return response()->json(new PerfilResource($perfil), 200);
    }

    // UPDATE adaptado a singleton: no espera Perfil $perfil por parámetro
    public function update(UpdatePerfilRequest $request): JsonResponse
    {
        // Validar campos de texto
        $data = $request->validated();

        // Buscar perfil existente (singleton) o crear uno nuevo
        $perfil = Perfil::first();
        if (! $perfil) {
            // crea con los campos validados; archivos serán manejados abajo
            $perfil = Perfil::create($data);
        } else {
            // actualiza campos de texto
            $perfil->update($data);
        }

        // Borrar archivos si se pidió (usa public_id si existe)
        if ($request->boolean('remove_avatar') && $perfil->foto_hero_public_id) {
            try {
                $this->cloud->destroy($perfil->foto_hero_public_id, 'image');
            } catch (\Throwable $e) {
                // log si quieres
            }
            $perfil->update(['foto_hero_url' => null, 'foto_hero_public_id' => null]);
        }
        if ($request->boolean('remove_foto_sobre_mi') && $perfil->foto_sobre_mi_public_id) {
            try {
                $this->cloud->destroy($perfil->foto_sobre_mi_public_id, 'image');
            } catch (\Throwable $e) {
            }
            $perfil->update(['foto_sobre_mi_url' => null, 'foto_sobre_mi_public_id' => null]);
        }
        if ($request->boolean('remove_cv') && $perfil->cv_public_id) {
            try {
                $this->cloud->destroy($perfil->cv_public_id, 'raw');
            } catch (\Throwable $e) {
            }
            $perfil->update(['cv_url' => null, 'cv_public_id' => null, 'cv_filename' => null]);
        }

        // Reemplazar avatar (si se subió uno nuevo)
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($perfil->foto_hero_public_id) {
                try {
                    $this->cloud->destroy($perfil->foto_hero_public_id, 'image');
                } catch (\Throwable $e) {
                }
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
                try {
                    $this->cloud->destroy($perfil->foto_sobre_mi_public_id, 'image');
                } catch (\Throwable $e) {
                }
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
                try {
                    $this->cloud->destroy($perfil->cv_public_id, 'raw');
                } catch (\Throwable $e) {
                }
            }
            $name = pathinfo($request->file('cv')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadRaw(
                $request->file('cv')->getRealPath(),
                'perfiles/cv',
                [
                    'resource_type'     => 'raw',
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'format'            => 'pdf',
                    'unique_filename'   => false,
                    'overwrite'         => true,
                    'access_mode'       => 'public',
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

    public function destroy(): JsonResponse
    {
        $perfil = Perfil::first();
        if (!$perfil) {
            return response()->json(['message' => 'Perfil no encontrado'], 404);
        }

        // Borrar archivos en Cloudinary si existen
        if ($perfil->foto_hero_public_id) {
            try {
                $this->cloud->destroy($perfil->foto_hero_public_id, 'image');
            } catch (\Throwable $e) {
            }
        }
        if ($perfil->foto_sobre_mi_public_id) {
            try {
                $this->cloud->destroy($perfil->foto_sobre_mi_public_id, 'image');
            } catch (\Throwable $e) {
            }
        }
        if ($perfil->cv_public_id) {
            try {
                $this->cloud->destroy($perfil->cv_public_id, 'raw');
            } catch (\Throwable $e) {
            }
        }

        $perfil->delete();

        return response()->json(['message' => 'Perfil eliminado correctamente'], 200);
    }
   public function descargarCv()
{
    $perfil = \App\Models\Perfil::first();
    if (!$perfil || !$perfil->cv_url) {
        return response()->json(['message' => 'CV no encontrado'], 404);
    }

    $original = $perfil->cv_url; // ejemplo: https://res.cloudinary.com/.../raw/upload/v1756062197/perfiles/cv/erik-quisnia.pdf
    $filename = $perfil->cv_filename ?? basename(parse_url($original, PHP_URL_PATH) ?: 'cv.pdf');

    // parsear la URL
    $parts = parse_url($original);
    if (!$parts || !isset($parts['host']) || !isset($parts['path'])) {
        // si no se puede parsear, fallback: redirigir a la URL original
        return redirect()->away($original);
    }

    // buscamos la porción después de "/raw/upload/"
    $needle = '/raw/upload/';
    $pos = strpos($parts['path'], $needle);
    if ($pos === false) {
        // si no tiene la forma esperada, fallback
        return redirect()->away($original);
    }

    $after = substr($parts['path'], $pos + strlen($needle)); // ej: v1756062197/perfiles/cv/erik-quisnia.pdf

    // construir la URL con fl_attachment y filename url-encoded
    $downloadFilename = rawurlencode($filename);
    $downloadUrl = ($parts['scheme'] ?? 'https') . '://' . $parts['host'] . "/raw/upload/fl_attachment:{$downloadFilename}/" . ltrim($after, '/');

    // redirigir al recurso transformado (Cloudinary debería forzar la descarga)
    return redirect()->away($downloadUrl);
}

}
