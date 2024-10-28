<?php

namespace App\Action\AboutImage;

use App\Dto\AboutImage\IndexDto;
use App\Dto\AboutImage\UpdateDto;
use App\Helper\MediaObject;
use App\Models\AboutImage;
use Illuminate\Support\Facades\Storage;

final class UpdateAction
{
    public function __invoke(UpdateDto $dto): IndexDto
    {
        $aboutImage = AboutImage::find($dto->id);
        [$data, $deleted_files] = $this->updateMediaObject(array_filter($dto->toArray()), $aboutImage);
        $data = $this->updateText($data, $aboutImage);
        $aboutImage->update($data);
        Storage::disk('public')->delete($deleted_files);

        return IndexDto::from($aboutImage);
    }

    private function updateMediaObject(array $data, AboutImage $aboutImage): array
    {
        $deleted_files = [];
        if ($data['image']) {
            $data['image'] = MediaObject::upload($data['image']);
            $deleted_files[] = $aboutImage->image;
        }
        
        return [$data, $deleted_files];
    }

    private function updateText(array $data, AboutImage $aboutImage): array
    {
        $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $aboutImage->alt['ru'] ?? "";
        $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $aboutImage->alt['en'] ?? "";
        $data['description']['en'] = !empty($data['description']['en']) ? $data['description']['en'] : $aboutImage->description['en'] ?? "";
        $data['description']['ru'] = !empty($data['description']['ru']) ? $data['description']['ru'] : $aboutImage->description['ru'] ?? "";
        $data['title']['en'] = !empty($data['title']['en']) ? $data['title']['en'] : $aboutImage->title['en'] ?? "";
        $data['title']['ru'] = !empty($data['title']['ru']) ? $data['title']['ru'] : $aboutImage->title['ru'] ?? "";
        
        return $data;
    }
}
