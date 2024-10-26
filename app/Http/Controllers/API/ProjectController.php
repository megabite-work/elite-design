<?php

namespace App\Http\Controllers\API;

use App\Action\Project\CreateAction;
use App\Action\Project\DeleteAction;
use App\Action\Project\IndexAction;
use App\Action\Project\ShowAction;
use App\Action\Project\UpdateAction;
use App\Dto\Project\CreateDto;
use App\Dto\Project\QueryDto;
use App\Dto\Project\UpdateDto;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProjectController extends BaseController implements HasMiddleware
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
        return $this->sendResponse($action($dto), "Project successfully created");
    }

    public function show(int $id, ShowAction $action): JsonResponse
    {
        return $this->sendResponse($action($id));
    }

    public function update(UpdateDto $dto, UpdateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Project successfully updated");
    }

    public function destroy(int $id, DeleteAction $action): JsonResponse
    {
        return $this->sendResponse($action($id), "Project successfully deleted");
    }
}
