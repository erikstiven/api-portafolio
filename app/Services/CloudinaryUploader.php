<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryUploader
{
    private Cloudinary $cloud;

    public function __construct()
    {
        $url = config('cloudinary.cloud_url') ?? env('CLOUDINARY_URL');
        $this->cloud = new Cloudinary($url);
    }

    private function upload(string $localPath, string $folder, string $resourceType = 'image', array $options = []): array
    {
        $opts = array_merge([
            'folder'        => $folder,
            'resource_type' => $resourceType, // 'image' | 'raw' | 'video' | 'auto'
            'access_mode'   => 'public',      // clave: forzar acceso público
        ], $options);

        $res = $this->cloud->uploadApi()->upload($localPath, $opts);

        return [
            'secure_url' => $res['secure_url'] ?? null,
            'url'        => $res['url'] ?? ($res['secure_url'] ?? null),
            'public_id'  => $res['public_id'] ?? null,
        ];
    }

    public function uploadImage(string $path, string $folder = 'proyectos', array $options = []): array
    {
        return $this->upload($path, $folder, 'image', $options);
    }

    public function uploadRaw(string $path, string $folder = 'perfiles/cv', array $options = []): array
    {
        // fuerza que los raw (ej. PDFs) sean públicos
        $options = array_merge(['access_mode' => 'public'], $options);

        return $this->upload($path, $folder, 'raw', $options);
    }

    public function destroy(string $publicId, string $resourceType = 'image'): bool
    {
        $res = $this->cloud->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
        return ($res['result'] ?? '') === 'ok';
    }
}
