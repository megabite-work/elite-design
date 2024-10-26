<?php

namespace App\Dto\Category;

use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateDto extends Data
{
    public function __construct(
        #[Unique('categories', 'name')]
        public string $name,
        public ?int $sort,
    ) {}
}
