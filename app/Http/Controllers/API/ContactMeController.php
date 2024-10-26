<?php

namespace App\Http\Controllers\API;

use App\Action\ContactMe\SendMailAction;
use App\Dto\ContactMe\SendMailDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactMeController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['contactMe']),
        ];
    }

    public function contactMe(SendMailDto $dto, SendMailAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Mail successfully send");
    }
}
