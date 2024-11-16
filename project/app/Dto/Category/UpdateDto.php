<?php

namespace App\Dto\Category;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

class UpdateDto extends Data
{
    public function __construct(
        #[FromRouteParameter('category')]
        #[Exists('categories', 'id')]
        public int $id,
        #[Unique('categories', 'name', ignore: new RouteParameterReference('category'))]
        public ?string $name,
    ) {}
}
