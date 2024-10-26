<?php

namespace App\Dto\BannerImage;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateDto extends Data
{
    public function __construct(
        #[Exists('categories', 'id')]
        public int $category_id,
        public ?array $alt,
        public array $images,
        public array $formats,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'images' => ['bail', 'required', 'array'],
            'images.*' => ['bail', 'required', 'image', 'max:10240'],
            'formats' => ['bail', 'required', 'array'],
            'formats.*' => ['bail', 'required', 'string', 'max:255'],
        ];
    }
}
