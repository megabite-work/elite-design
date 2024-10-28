<?php

namespace App\Dto\WebSetting;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateDto extends Data
{
    public function __construct(
        public ?array $alt,
        public array $images,
        public array $about,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'about' => ['bail', 'required', 'array'],
            'about.*' => ['bail', 'required', 'string'],
            'images' => ['bail', 'required', 'array'],
            'images.*' => ['bail', 'required', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array'],
            'alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
        ];
    }
}
