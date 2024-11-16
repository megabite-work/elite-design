<?php

namespace App\Dto\Auth;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class LoginDto extends Data
{
    public function __construct(
        #[Max(255)]
        public string $login,
        #[Max(255)]
        public string $password,
    ) {}
}