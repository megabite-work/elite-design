<?php

namespace App\Action\Auth;

use App\Dto\Auth\ChangePasswordDto;
use App\Exception\ErrorException;
use Illuminate\Support\Facades\Hash;

final class ChangePasswordAction
{
    public function __invoke(ChangePasswordDto $dto): array
    {
        $user = auth()->user();
        $data = $dto->toArray();
        
        if (! Hash::check($data["old_password"], $user->password)) {
            throw new ErrorException("Wrong password", ['error' => 'Wrong password'], 400);
        }
        
        $user->update(['password' => bcrypt($data["new_password"])]);
        auth()->logout();

        return [];
    }
}