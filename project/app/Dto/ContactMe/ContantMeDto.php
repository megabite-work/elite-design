<?php

namespace App\Dto\ContactMe;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class ContantMeDto extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,
        #[Max(255)]
        public string $phone,
    ) {}
}
