<?php

namespace App\Dto\Auth;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class ChangePasswordDto extends Data
{
    public function __construct(
        #[Max(255)]
        public string $old_password,
        #[Max(255)]
        public string $new_password,
    ) {}
}
