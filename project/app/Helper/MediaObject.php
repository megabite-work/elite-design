<?php

namespace App\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaObject
{
    public static function upload(UploadedFile $file, string $path = 'images'): string
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        if (!$path = $file->storeAs($path, $fileName, 'public')) {
            return null;
        }

        return $path;
    }

    public static function uploadBase64Image(string $base64_image, string $path = 'images')
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $type = strtolower($type[1]);

            $data = base64_decode($data);

            $filePath = $path . '/' . uniqid() . '.' . $type;

            if (!Storage::disk('public')->put($filePath, $data)) {
                return 'error';
            }

            return $filePath;
        } else {
            return null;
        }
    }

    public static function uploadBase64Audio(string $base64_image, string $path = 'audios')
    {
        if (preg_match('/^data:audio\/(\w+);base64,/', $base64_image, $type)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $type = strtolower($type[1]);

            $data = base64_decode($data);

            $filePath = $path . '/' . uniqid() . '.' . $type;

            if (!Storage::disk('public')->put($filePath, $data)) {
                return 'error';
            }

            return $filePath;
        } else {
            return null;
        }
    }
}
