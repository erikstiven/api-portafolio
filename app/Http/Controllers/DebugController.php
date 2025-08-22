<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DebugController extends Controller
{
    public function cloudinary()
    {
        return response()->json([
            'config'  => config('cloudinary'),
            'url_env' => env('CLOUDINARY_URL'),
        ]);
    }

    // app/Http/Controllers/DebugController.php
    public function upload(Request $r)
    {
        // Validación mínima para aislar el problema
        $r->validate(['file' => ['required', 'file']]);

        $path = $r->file('file')->getRealPath();

        // Usa el SDK directo, sin facade ni provider
        $cloudinaryUrl = config('cloudinary.cloud_url') ?? env('CLOUDINARY_URL');
        $cloudinary = new \Cloudinary\Cloudinary($cloudinaryUrl);

        $res = $cloudinary->uploadApi()->upload($path, [
            'folder'        => 'debug',
            'resource_type' => 'auto',
        ]);

        return response()->json([
            'url'      => $res['secure_url'] ?? null,
            'publicId' => $res['public_id'] ?? null,
        ]);
    }
}
