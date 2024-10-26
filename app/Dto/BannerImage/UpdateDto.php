<?php

namespace App\Dto\BannerImage;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateDto extends Data
{
    public function __construct(
        #[FromRouteParameter('banner_image')]
        #[Exists('banner_images', 'id')]
        public int $id,
        public array $alt = [],
        public array $images = [],
        public array $formats = [],
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'images' => ['bail', 'nullable', 'array'],
            'images.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'formats' => ['bail', 'nullable', 'array'],
            'formats.*' => ['bail', 'nullable', 'string', 'max:255'],
        ];
    }
}
