<?php

namespace App\Action\AboutImage;

use App\Dto\AboutImage\CreateDto;
use App\Dto\AboutImage\IndexDto;
use App\Helper\MediaObject;
use App\Models\AboutImage;

final class CreateAction
{
    public function __invoke(CreateDto $dto): IndexDto
    {
        $data = $dto->only('title', 'alt', 'description')->toArray();
        $data['image'] = MediaObject::upload($dto->image);
        $data['sort'] = (AboutImage::latest()->first()->id ?? 0) + 1;

        return IndexDto::from(AboutImage::create($data));
    }
}
