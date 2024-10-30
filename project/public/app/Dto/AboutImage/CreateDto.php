<?php

namespace App\Dto\AboutImage;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateDto extends Data
{
    public function __construct(
        public array $title,
        #[Image]
        public UploadedFile $image,
        public array $alt = [],
        public array $description,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'title' => ['bail', 'required', 'array'],
            'title.*' => ['bail', 'required', 'string', 'max:255'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'description' => ['bail', 'required', 'array'],
            'description.*' => ['bail', 'required', 'string'],
        ];
    }
}
