<?php

namespace App\Support;

class CloudinaryUrl
{
    public static function downloadUrl(string $publicId, string $filename, string $resourceType = 'raw'): string
    {
        $cloud = env('CLOUDINARY_CLOUD_NAME');
        // https://res.cloudinary.com/{cloud}/{resource}/upload/fl_attachment:{filename}/{publicId}
        return sprintf(
            'https://res.cloudinary.com/%s/%s/upload/fl_attachment:%s/%s',
            $cloud,
            $resourceType,
            rawurlencode($filename),
            $publicId
        );
    }
}
