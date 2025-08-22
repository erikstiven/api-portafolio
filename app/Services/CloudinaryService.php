<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Exception\ApiError;

class CloudinaryService
{
    private Cloudinary $cloud;

    public function __construct(?string $cloudinaryUrl = null)
    {
        // Toma URL de config/cloudinary.php o .env
        $url = $cloudinaryUrl ?? config('cloudinary.cloud_url') ?? env('CLOUDINARY_URL');
        $this->cloud = new Cloudinary($url);
    }

    /**
     * Subida genérica.
     * $resourceType: 'image' | 'raw' | 'video'
     * $options: folder, use_filename, filename_override, unique_filename, overwrite, transformation, format, etc.
     */
    public function upload(string $localPath, string $resourceType = 'image', array $options = []): array
    {
        try {
            $opts = array_merge([
                'resource_type' => $resourceType,
            ], $options);

            $res = $this->cloud->uploadApi()->upload($localPath, $opts);

            return [
                'secure_url' => $res['secure_url'] ?? null,
                'url'        => $res['url']        ?? null,
                'public_id'  => $res['public_id']  ?? null,
                'bytes'      => $res['bytes']      ?? null,
                'width'      => $res['width']      ?? null,
                'height'     => $res['height']     ?? null,
                'format'     => $res['format']     ?? null,
                'folder'     => $res['folder']     ?? null,
                'resource_type' => $resourceType,
            ];
        } catch (ApiError $e) {
            throw new \RuntimeException('Cloudinary upload failed: '.$e->getMessage(), 0, $e);
        }
    }

    /** Conveniencia: imagen */
    public function uploadImage(string $localPath, string $folder = 'uploads/images', array $options = []): array
    {
        $options = array_merge(['folder' => $folder], $options);
        return $this->upload($localPath, 'image', $options);
    }

    /** Conveniencia: raw (PDF, ZIP, etc.) */
    public function uploadRaw(string $localPath, string $folder = 'uploads/raw', array $options = []): array
    {
        $options = array_merge(['folder' => $folder], $options);
        return $this->upload($localPath, 'raw', $options);
    }

    /** Borrar por public_id y tipo */
    public function destroy(string $publicId, string $resourceType = 'image'): bool
    {
        try {
            $res = $this->cloud->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
            return ($res['result'] ?? '') === 'ok';
        } catch (ApiError $e) {
            throw new \RuntimeException('Cloudinary destroy failed: '.$e->getMessage(), 0, $e);
        }
    }

    /** (Opcional) Reemplazo práctico: borra antiguo y sube nuevo */
    public function replace(?string $oldPublicId, string $newLocalPath, string $resourceType, array $options = []): array
    {
        if ($oldPublicId) {
            // Ignora error de delete para no romper reemplazos
            try { $this->destroy($oldPublicId, $resourceType); } catch (\Throwable $t) {}
        }
        return $this->upload($newLocalPath, $resourceType, $options);
    }
}
