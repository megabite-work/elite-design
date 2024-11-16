<?php

namespace App\Action\WebSetting;

use App\Dto\WebSetting\CreateDto;
use App\Dto\WebSetting\IndexDto;
use App\Helper\MediaObject;
use App\Models\WebSetting;

final class CreateAction
{
    public function __invoke(CreateDto $dto): IndexDto
    {
        $data = $dto->only('about', 'alt')->toArray();
        foreach ($dto->images as $key => $image) {
            $data['images'][$key]['image'] = MediaObject::upload($image);
            $data['images'][$key]['alt'] = !empty($data['alt'][$key]) ? $data['alt'][$key] : null;
        }
        ksort($data['images']);
        unset($data['alt']);

        return IndexDto::from(WebSetting::create($data));
    }
}
