<?php

namespace App\Action\BannerImage;

use App\Dto\BannerImage\IndexDto;
use App\Dto\BannerImage\QueryDto;
use App\Models\BannerImage;
use Spatie\LaravelData\PaginatedDataCollection;

final class IndexAction
{
    public function __invoke(QueryDto $dto): mixed
    {
        return IndexDto::collect(BannerImage::orderBy('sort', 'ASC')->paginate($dto->per_page), PaginatedDataCollection::class);
    }
}
