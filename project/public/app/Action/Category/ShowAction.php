<?php

namespace App\Action\Category;

use App\Dto\Category\IndexDto;
use App\Exception\ErrorException;
use App\Models\Category;

final class ShowAction
{
    public function __invoke(int $id): IndexDto
    {
        $category = Category::with('bannerImages')->find($id);

        if (!$category) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        return IndexDto::from($category);
    }
}
