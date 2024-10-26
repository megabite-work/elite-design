<?php

namespace App\Action\BannerImage;

use App\Dto\BannerImage\IndexDto;
use App\Exception\ErrorException;
use App\Models\BannerImage;

final class ShowAction
{
    public function __invoke(int $id): IndexDto
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        return IndexDto::from($bannerImage);
    }
}
