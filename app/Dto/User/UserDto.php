<?php

namespace App\Dto\User;

use DateTime;
use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public int $id,
        public string $login,
        public DateTime $created_at,
    ) {}
}