<?php

namespace App\Dto\AboutImage;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateDto extends Data
{
    public function __construct(
        #[FromRouteParameter('about_image')]
        #[Exists('about_images', 'id')]
        public int $id,
        public array $title = [],
        public array $alt = [],
        #[Image]
        public ?UploadedFile $image,
        public array $description = [],
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'title' => ['bail', 'nullable', 'array'],
            'title.*' => ['bail', 'nullable', 'string', 'max:255'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'description' => ['bail', 'nullable', 'array'],
            'description.*' => ['bail', 'nullable', 'string'],
        ];
    }
}
