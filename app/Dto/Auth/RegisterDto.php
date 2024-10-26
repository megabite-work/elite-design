<?php

namespace App\Dto\Auth;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RegisterDto extends Data
{
    public function __construct(
        #[Max(255)]
        #[Min(3)]
        #[Unique('users', 'login')]
        public string $login,
        public ?string $email,
        public string $password,
    ) {}
}
