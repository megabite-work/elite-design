<?php

namespace App\Dto\BannerImage;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class SortDto extends Data
{
    public function __construct(
        public array $items = [],
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'items' => ['bail', 'nullable', 'array'],
            'items.*.id' => ['bail', 'nullable', 'integer', 'exists:banner_images,id'],
            'items.*.sort' => ['bail', 'nullable', 'integer'],
        ];
    }
}
