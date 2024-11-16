<?php

namespace App\Http\Controllers\API;

use App\Action\ContactMe\ContantMeAction;
use App\Dto\ContactMe\ContantMeDto;
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

    public function contactMe(ContantMeDto $dto, ContantMeAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Mail successfully send");
    }
}
