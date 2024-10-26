<?php

namespace App\Action\BannerImage;

use App\Dto\BannerImage\CreateDto;
use App\Dto\BannerImage\IndexDto;
use App\Helper\MediaObject;
use App\Models\BannerImage;

final class CreateAction
{
    public function __invoke(CreateDto $dto): IndexDto
    {
        $data = $dto->only('category_id', 'alt')->toArray();
        foreach ($dto->images as $key => $image) {
            $data['images'][$key]['image'] = MediaObject::upload($image);
            $data['images'][$key]['format'] = !empty($dto->formats[$key]) ? $dto->formats[$key] : "";
        }
        ksort($data['images']);
        $data['sort'] = (BannerImage::latest()->first()->id ?? 0) + 1;

        return IndexDto::from(BannerImage::create($data));
    }
}
