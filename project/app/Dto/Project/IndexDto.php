<?php

namespace App\Dto\Project;

use DateTime;
use Spatie\LaravelData\Data;

class IndexDto extends Data
{
    public function __construct(
        public int $id,
        public array $title,
        public array $short_description,
        public array $city,
        public int $year,
        public array $description,
        public array $files,
        public string $image,
        public ?array $alt,
        public array $pictures,
        public array $characteristics,
        public array $plans,
        public array $plan_photos,
        public string $video,
        public string $address,
        public string $longitude,
        public string $latitude,
        public DateTime $created_at,
    ) {}
}
