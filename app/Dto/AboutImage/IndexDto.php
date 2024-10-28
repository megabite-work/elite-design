<?php

namespace App\Dto\AboutImage;

use Spatie\LaravelData\Data;

class IndexDto extends Data
{
    public function __construct(
        public int $id,
        public array $title,
        public string $image,
        public ?array $alt,
        public array $description,
        public int $sort,
        public \DateTime $created_at,
    ) {}
}
