<?php

namespace App\Action\AboutImage;

use App\Dto\AboutImage\IndexDto;
use App\Exception\ErrorException;
use App\Models\AboutImage;

final class ShowAction
{
    public function __invoke(int $id): IndexDto
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        return IndexDto::from($aboutImage);
    }
}
