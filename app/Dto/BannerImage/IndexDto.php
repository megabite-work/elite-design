<?php

namespace App\Dto\BannerImage;

use Spatie\LaravelData\Data;

class IndexDto extends Data
{
    public function __construct(
        public int $id,
        public int $category_id,
        public ?array $alt,
        public array $images,
        public int $sort,
        public \DateTime $created_at,
    ) {}
}
