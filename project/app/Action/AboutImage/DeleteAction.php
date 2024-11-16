<?php

namespace App\Action\AboutImage;

use App\Exception\ErrorException;
use App\Models\AboutImage;
use Illuminate\Support\Facades\Storage;

final class DeleteAction
{
    public function __invoke(int $id): array
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        $image = $aboutImage->image;
        $aboutImage->delete();

        if (Storage::disk('public')->exists($image)) Storage::disk('public')->delete($image);

        return [];
    }
}
