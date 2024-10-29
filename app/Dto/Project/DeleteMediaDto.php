<?php

namespace App\Dto\Project;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class DeleteMediaDto extends Data
{
    public function __construct(
        #[FromRouteParameter('project')]
        #[Exists('projects', 'id')]
        public int $id,
        public ?int $files,
        public ?int $pictures,
        public ?int $plan_photos,
    ) {}
}
