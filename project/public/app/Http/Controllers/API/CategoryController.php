<?php

namespace App\Http\Controllers\API;

use App\Action\Category\CreateAction;
use App\Action\Category\DeleteAction;
use App\Action\Category\IndexAction;
use App\Action\Category\ShowAction;
use App\Action\Category\SortAction;
use App\Action\Category\UpdateAction;
use App\Dto\Category\CreateDto;
use App\Dto\Category\QueryDto;
use App\Dto\Category\SortDto;
use App\Dto\Category\UpdateDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends BaseController implements HasMiddleware
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

    public function sort(SortDto $dto, SortAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Category successfully sorted");
    }

    public function store(CreateDto $dto, CreateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Category successfully created");
    }

    public function show(int $id, ShowAction $action): JsonResponse
    {
        return $this->sendResponse($action($id));
    }

    public function update(UpdateDto $dto, UpdateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Category successfully updated");
    }

    public function destroy(int $id, DeleteAction $action): JsonResponse
    {
        return $this->sendResponse($action($id), "Category successfully deleted");
    }
}
