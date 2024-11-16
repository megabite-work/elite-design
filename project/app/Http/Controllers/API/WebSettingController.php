<?php

namespace App\Http\Controllers\API;

use App\Action\WebSetting\CreateAction;
use App\Action\WebSetting\DeleteAction;
use App\Action\WebSetting\IndexAction;
use App\Action\WebSetting\ShowAction;
use App\Action\WebSetting\UpdateAction;
use App\Dto\WebSetting\CreateDto;
use App\Dto\WebSetting\QueryDto;
use App\Dto\WebSetting\UpdateDto;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WebSettingController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
        ];
    }
    public function index(QueryDto $dto, IndexAction $action): JsonResponse
    {
        return $this->sendIndexResponse($action($dto));
    }

    public function store(CreateDto $dto, CreateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Web Setting successfully created");
    }

    public function show(int $id, ShowAction $action): JsonResponse
    {
        return $this->sendResponse($action($id));
    }

    public function update(UpdateDto $dto, UpdateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Web Setting successfully updated");
    }

    public function destroy(int $id, DeleteAction $action): JsonResponse
    {
        return $this->sendResponse($action($id), "Web Setting successfully deleted");
    }
}
