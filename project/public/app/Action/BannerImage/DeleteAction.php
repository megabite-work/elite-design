<?php

namespace App\Action\BannerImage;

use App\Exception\ErrorException;
use App\Models\BannerImage;
use Illuminate\Support\Facades\Storage;

final class DeleteAction
{
    public function __invoke(int $id): array
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        $deleted_files = [];
        foreach ($bannerImage->images as $image) {
            if (Storage::disk('public')->exists($image['image'])) $deleted_files[] = $image['image'];
        }

        $bannerImage->delete();
        Storage::disk('public')->delete($deleted_files);

        return [];
    }
}
