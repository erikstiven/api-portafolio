<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Http\Resources\ServicioResource;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index(Request $r)
    {
        $page = max((int)$r->query('page',1),1);
        $size = min(max((int)$r->query('pageSize',10),1),50);

        $q = Servicio::orderByDesc('created_at');
        $total = $q->count();
        $items = $q->skip(($page-1)*$size)->take($size)->get();

        return response()->json([
            'items' => ServicioResource::collection($items),
            'page' => $page, 'pageSize' => $size,
            'total' => $total, 'totalPages' => (int)ceil($total/$size),
        ]);
    }

    public function show(Servicio $servicio)
    {
        return new ServicioResource($servicio);
    }

    public function store(StoreServicioRequest $req)
    {
        $servicio = Servicio::create($req->validated());
        return (new ServicioResource($servicio))->response()->setStatusCode(201);
    }

    public function update(UpdateServicioRequest $req, Servicio $servicio)
    {
        $servicio->update($req->validated());
        return new ServicioResource($servicio->refresh());
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return response()->noContent();
    }
}
