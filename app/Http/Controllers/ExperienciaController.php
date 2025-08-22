<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExperienciaRequest;
use App\Http\Requests\UpdateExperienciaRequest;
use App\Http\Resources\ExperienciaResource;
use App\Models\Experiencia;
use Illuminate\Http\Request;

class ExperienciaController extends Controller
{
    public function index(Request $r)
    {
        $page = max((int)$r->query('page',1),1);
        $size = min(max((int)$r->query('pageSize',10),1),50);

        $q = Experiencia::orderByDesc('fecha_inicio');
        $total = $q->count();
        $items = $q->skip(($page-1)*$size)->take($size)->get();

        return response()->json([
            'items' => ExperienciaResource::collection($items),
            'page' => $page, 'pageSize' => $size,
            'total' => $total, 'totalPages' => (int)ceil($total/$size),
        ]);
    }

    public function show(Experiencia $experiencia)
    {
        return new ExperienciaResource($experiencia);
    }

    public function store(StoreExperienciaRequest $req)
    {
        $exp = Experiencia::create($req->validated());
        return (new ExperienciaResource($exp))->response()->setStatusCode(201);
    }

    public function update(UpdateExperienciaRequest $req, Experiencia $experiencia)
    {
        $experiencia->update($req->validated());
        return new ExperienciaResource($experiencia->refresh());
    }

    public function destroy(Experiencia $experiencia)
    {
        $experiencia->delete();
        return response()->noContent();
    }
}
