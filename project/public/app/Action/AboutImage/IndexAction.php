<?php

namespace App\Action\AboutImage;

use App\Dto\AboutImage\IndexDto;
use App\Dto\AboutImage\QueryDto;
use App\Models\AboutImage;
use Spatie\LaravelData\PaginatedDataCollection;

final class IndexAction
{
    public function __invoke(QueryDto $dto): mixed
    {
        return IndexDto::collect(AboutImage::orderBy('sort', 'ASC')->paginate($dto->per_page), PaginatedDataCollection::class);
    }
}
