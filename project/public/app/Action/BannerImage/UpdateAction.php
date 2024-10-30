<?php

namespace App\Action\BannerImage;

use App\Dto\BannerImage\IndexDto;
use App\Dto\BannerImage\UpdateDto;
use App\Helper\MediaObject;
use App\Models\BannerImage;
use Illuminate\Support\Facades\Storage;

final class UpdateAction
{
    public function __invoke(UpdateDto $dto): IndexDto
    {
        $bannerImage = BannerImage::find($dto->id);
        [$data, $deleted_files] = $this->updateMediaObject(array_filter($dto->toArray()), $bannerImage);
        $data = $this->updateText($data, $bannerImage);
        $bannerImage->update($data);
        Storage::disk('public')->delete($deleted_files);

        return IndexDto::from($bannerImage);
    }

    private function updateMediaObject(array $data, BannerImage $bannerImage): array
    {
        $deleted_files = [];
        $images = array_values($bannerImage->images);
        foreach ($data['images'] ?? [] as $key => $image) {
            if (!empty($images[$key]['image']) && Storage::disk('public')->exists($images[$key]['image'])) $deleted_files[] = $images[$key]['image'];

            $images[$key]['image'] = MediaObject::upload($image);
        }
        
        foreach ($data['formats'] ?? [] as $key => $value) {
            $images[$key]['format'] = !empty($value) ? $value : $images[$key]['format'] ?? "";
        }
        ksort($data['images']);
        $data['images'] = array_values($images);
        unset($data['formats']);

        return [$data, $deleted_files];
    }

    private function updateText(array $data, BannerImage $bannerImage): array
    {
        $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $bannerImage->alt['ru'] ?? "";
        $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $bannerImage->alt['en'] ?? "";
        
        unset($data['formats']);

        return $data;
    }
}
