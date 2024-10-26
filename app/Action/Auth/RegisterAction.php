<?php

namespace App\Action\Auth;

use App\Dto\Auth\RegisterDto;
use App\Dto\User\UserDto;
use App\Models\User;

final class RegisterAction
{
    public function __invoke(RegisterDto $dto): UserDto
    {
        $data = $dto->toArray();
        $data['password'] = bcrypt($data['password']);

        return UserDto::from(User::create($data));
    }
}
