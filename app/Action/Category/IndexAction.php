<?php

namespace App\Action\Category;

use App\Dto\Category\IndexDto;
use App\Dto\Category\QueryDto;
use App\Models\Category;
use Spatie\LaravelData\PaginatedDataCollection;

final class IndexAction
{
    public function __invoke(QueryDto $dto): mixed
    {
        return IndexDto::collect(Category::with('bannerImages')->orderBy('sort', 'ASC')->paginate($dto->per_page), PaginatedDataCollection::class)
            ->except('bannerImages.category_id');
    }
}
