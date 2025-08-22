<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $r)
    {
        $page = max((int)$r->query('page',1),1);
        $size = min(max((int)$r->query('pageSize',10),1),50);

        $q = Categoria::orderBy('nombre');
        $total = $q->count();
        $items = $q->skip(($page-1)*$size)->take($size)->get();

        return response()->json([
            'items' => CategoriaResource::collection($items),
            'page' => $page, 'pageSize' => $size,
            'total' => $total, 'totalPages' => (int)ceil($total/$size),
        ]);
    }

    public function show(Categoria $categoria)
    {
        return new CategoriaResource($categoria);
    }

    public function store(StoreCategoriaRequest $req)
    {
        $categoria = Categoria::create($req->validated());
        return (new CategoriaResource($categoria))->response()->setStatusCode(201);
    }

    public function update(UpdateCategoriaRequest $req, Categoria $categoria)
    {
        $categoria->update($req->validated());
        return new CategoriaResource($categoria->refresh());
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->noContent();
    }
}
