<?php

namespace App\Dto\Category;

use Spatie\LaravelData\Data;

class IndexDto extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $sort,
        public \DateTime $created_at,
        /** @var App\Dto\BannerImage\IndexDto[] */
        public ?array $bannerImages,
    ) {}
}
