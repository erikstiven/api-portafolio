<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;
use App\Http\Resources\ProyectoResource;
use App\Models\Proyecto;
use App\Services\CloudinaryUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProyectoController extends Controller
{
    public function __construct(private CloudinaryUploader $cloud) {}

    public function index(): JsonResponse
    {
        $q = Proyecto::query()->latest();

        // Filtros opcionales
        if (request()->filled('categoria_id')) {
            $q->where('categoria_id', request('categoria_id'));
        }
        if (request()->filled('destacado')) {
            $q->where('destacado', filter_var(request('destacado'), FILTER_VALIDATE_BOOLEAN));
        }
        if (request()->filled('search')) {
            $s = trim(request('search'));
            $q->where(function($w) use ($s) {
                $w->where('titulo', 'like', "%$s%")
                  ->orWhere('tecnologias', 'like', "%$s%");
            });
        }

        $data = $q->paginate(12);
        return response()->json([
            'data' => ProyectoResource::collection($data->items()),
            'meta' => [
                'currentPage' => $data->currentPage(),
                'lastPage'    => $data->lastPage(),
                'perPage'     => $data->perPage(),
                'total'       => $data->total(),
            ],
        ]);
    }

    public function show(Proyecto $proyecto): ProyectoResource
    {
        return new ProyectoResource($proyecto);
    }

    public function store(StoreProyectoRequest $request): JsonResponse
    {
        $proyecto = Proyecto::create($request->validated());

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $name = pathinfo($request->file('imagen')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadImage(
                $request->file('imagen')->getRealPath(),
                'proyectos/imagenes',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $proyecto->update([
                'imagen_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'imagen_public_id' => $up['public_id'] ?? null,
            ]);
        }

        return response()->json(new ProyectoResource($proyecto->fresh()), 201);
    }

    public function update(UpdateProyectoRequest $request, Proyecto $proyecto): JsonResponse
    {
        $proyecto->update($request->validated());

        if ($request->boolean('remove_imagen') && $proyecto->imagen_public_id) {
            $this->cloud->destroy($proyecto->imagen_public_id, 'image');
            $proyecto->update(['imagen_url' => null, 'imagen_public_id' => null]);
        }

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            if ($proyecto->imagen_public_id) {
                $this->cloud->destroy($proyecto->imagen_public_id, 'image');
            }
            $name = pathinfo($request->file('imagen')->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($name);
            $up = $this->cloud->uploadImage(
                $request->file('imagen')->getRealPath(),
                'proyectos/imagenes',
                [
                    'use_filename'      => true,
                    'filename_override' => $slug,
                    'unique_filename'   => false,
                    'overwrite'         => true,
                ]
            );
            $proyecto->update([
                'imagen_url'       => $up['secure_url'] ?? $up['url'] ?? null,
                'imagen_public_id' => $up['public_id'] ?? null,
            ]);
        }

        return response()->json(new ProyectoResource($proyecto->fresh()), 200);
    }

    public function destroy(Proyecto $proyecto): JsonResponse
    {
        if ($proyecto->imagen_public_id) {
            $this->cloud->destroy($proyecto->imagen_public_id, 'image');
        }
        $proyecto->delete();
        return response()->json(null, 204);
    }

    
}
