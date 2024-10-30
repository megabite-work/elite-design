<?php

namespace App\Action\Category;

use App\Dto\Category\CreateDto;
use App\Dto\Category\IndexDto;
use App\Models\Category;

final class CreateAction
{
    public function __invoke(CreateDto $dto): IndexDto
    {
        $data = $dto->toArray();
        $data['sort'] = (Category::latest()->first()->id ?? 0) + 1;

        return IndexDto::from(Category::create($data))->except('bannerImages');
    }
}
