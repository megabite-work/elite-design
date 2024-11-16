<?php

namespace App\Dto\Project;

use Spatie\LaravelData\Data;

class QueryDto extends Data
{
    public function __construct(
        public int $per_page = 10,
        public int $page = 1,
    ) {}
}
