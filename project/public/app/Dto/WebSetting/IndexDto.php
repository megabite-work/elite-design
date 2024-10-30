<?php

namespace App\Dto\WebSetting;

use Spatie\LaravelData\Data;

class IndexDto extends Data
{
    public function __construct(
        public int $id,
        public array $about,
        public array $images,
        public \DateTime $created_at,
    ) {}
}
