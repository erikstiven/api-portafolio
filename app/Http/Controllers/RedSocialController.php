<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRedSocialRequest;
use App\Http\Requests\UpdateRedSocialRequest;
use App\Http\Resources\RedSocialResource;
use App\Models\RedSocial;
use Illuminate\Http\Request;

class RedSocialController extends Controller
{
    public function index(Request $r)
    {
        $page = max((int)$r->query('page',1),1);
        $size = min(max((int)$r->query('pageSize',10),1),50);

        $q = RedSocial::orderBy('nombre');
        $total = $q->count();
        $items = $q->skip(($page-1)*$size)->take($size)->get();

        return response()->json([
            'items' => RedSocialResource::collection($items),
            'page' => $page, 'pageSize' => $size,
            'total' => $total, 'totalPages' => (int)ceil($total/$size),
        ]);
    }

    public function show(RedSocial $redSocial)
    {
        return new RedSocialResource($redSocial);
    }

    public function store(StoreRedSocialRequest $req)
    {
        $red = RedSocial::create($req->validated());
        return (new RedSocialResource($red))->response()->setStatusCode(201);
    }

    public function update(UpdateRedSocialRequest $req, RedSocial $redSocial)
    {
        $redSocial->update($req->validated());
        return new RedSocialResource($redSocial->refresh());
    }

    public function destroy(RedSocial $redSocial)
    {
        $redSocial->delete();
        return response()->noContent();
    }
}
