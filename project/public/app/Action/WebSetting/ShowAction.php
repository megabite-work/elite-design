<?php

namespace App\Action\WebSetting;

use App\Dto\WebSetting\IndexDto;
use App\Exception\ErrorException;
use App\Models\WebSetting;

final class ShowAction
{
    public function __invoke(int $id): IndexDto
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        return IndexDto::from($webSetting);
    }
}
