<?php

namespace App\Http\Controllers\API;

use App\Action\Auth\ChangePasswordAction;
use App\Action\Auth\RegisterAction;
use App\Dto\Auth\ChangePasswordDto;
use App\Dto\Auth\LoginDto;
use App\Dto\Auth\RegisterDto;
use App\Dto\User\UserDto;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['register', 'login', 'showLogin']),
        ];
    }

    public function showLogin()
    {
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
    }

    public function register(RegisterDto $dto, RegisterAction $action): JsonResponse
    {
        return $this->sendResponse(['user' => $action($dto)], 'User register successfully.');
    }


    public function login(LoginDto $dto): JsonResponse
    {
        if (! $token = auth()->attempt($dto->toArray())) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }

        $success = $this->respondWithToken($token);

        return $this->sendResponse($success, 'User login successfully.');
    }

    public function changePassword(ChangePasswordDto $dto, ChangePasswordAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), 'Successfully password updated.');
    }

    public function me(): JsonResponse
    {
        return $this->sendResponse(UserDto::from(auth()->user()));
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->sendResponse([], 'Successfully logged out.');
    }

    public function refresh(): JsonResponse
    {
        $success = $this->respondWithToken(auth()->refresh());

        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
