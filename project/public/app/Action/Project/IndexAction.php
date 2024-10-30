<?php

namespace App\Action\Project;

use App\Dto\Project\IndexDto;
use App\Dto\Project\QueryDto;
use App\Models\Project;
use Spatie\LaravelData\PaginatedDataCollection;

final class IndexAction
{
    public function __invoke(QueryDto $dto): mixed
    {
        return IndexDto::collect(Project::paginate($dto->per_page), PaginatedDataCollection::class);
    }
}
