<?php

namespace App\Action\Category;

use App\Dto\Category\IndexDto;
use App\Dto\Category\UpdateDto;
use App\Models\Category;

final class UpdateAction
{
    public function __invoke(UpdateDto $dto): IndexDto
    {
        $category = Category::find($dto->id);
        $category->update(array_filter($dto->toArray()));

        return IndexDto::from($category);
    }
}
