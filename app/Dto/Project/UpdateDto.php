<?php

namespace App\Dto\Project;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateDto extends Data
{
    public function __construct(
        #[FromRouteParameter('project')]
        #[Exists('projects', 'id')]
        public int $id,
        public array $title = [],
        public array $short_description = [],
        public array $city = [],
        public ?int $year,
        public array $description = [],
        public array $files = [],
        public array $file_alt = [],
        #[Image]
        #[Max(10240)]
        public ?UploadedFile $image,
        public array $alt = [],
        public array $pictures = [],
        public array $picture_alt = [],
        public array $characteristics = [],
        public array $plans = [],
        public array $plan_photos = [],
        public array $plan_photo_alt = [],
        public ?string $video,
        public ?string $address,
        public ?string $longitude,
        public ?string $latitude,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'title' => ['bail', 'nullable', 'array'],
            'title.*' => ['bail', 'nullable', 'string', 'max:255'],
            'short_description' => ['bail', 'nullable', 'array'],
            'short_description.*' => ['bail', 'nullable', 'string', 'max:255'],
            'city' => ['bail', 'nullable', 'array'],
            'city.*' => ['bail', 'nullable', 'string', 'max:255'],
            'description' => ['bail', 'nullable', 'array'],
            'description.*' => ['bail', 'nullable', 'string'],
            'files' => ['bail', 'nullable', 'array'],
            'files.*' => ['bail', 'nullable', 'file', 'max:10240'],
            'file_alt' => ['bail', 'nullable', 'array'],
            'file_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'file_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'pictures' => ['bail', 'nullable', 'array'],
            'pictures.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'picture_alt' => ['bail', 'nullable', 'array'],
            'picture_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'picture_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'characteristics' => ['bail', 'nullable', 'array'],
            'characteristics.*' => ['bail', 'nullable', 'string'],
            'plans' => ['bail', 'nullable', 'array'],
            'plans.*' => ['bail', 'nullable', 'string'],
            'plan_photos' => ['bail', 'nullable', 'array'],
            'plan_photos.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'plan_photo_alt' => ['bail', 'nullable', 'array'],
            'plan_photo_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'plan_photo_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
        ];
    }
}
