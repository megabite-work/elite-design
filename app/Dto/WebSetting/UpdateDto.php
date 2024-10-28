<?php

namespace App\Dto\WebSetting;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateDto extends Data
{
    public function __construct(
        #[FromRouteParameter('web_setting')]
        #[Exists('web_settings', 'id')]
        public int $id,
        public ?array $alt,
        public ?array $images,
        public ?array $about,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'about' => ['bail', 'nullable', 'array'],
            'about.*' => ['bail', 'nullable', 'string'],
            'images' => ['bail', 'nullable', 'array'],
            'images.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array'],
            'alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
        ];
    }
}
