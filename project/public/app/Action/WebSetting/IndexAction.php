<?php

namespace App\Action\WebSetting;

use App\Dto\WebSetting\IndexDto;
use App\Dto\WebSetting\QueryDto;
use App\Models\WebSetting;
use Spatie\LaravelData\PaginatedDataCollection;

final class IndexAction
{
    public function __invoke(QueryDto $dto): mixed
    {
        return IndexDto::collect(WebSetting::paginate($dto->per_page), PaginatedDataCollection::class);
    }
}
