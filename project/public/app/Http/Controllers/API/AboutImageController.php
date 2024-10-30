<?php

namespace App\Http\Controllers\API;

use App\Action\AboutImage\CreateAction;
use App\Action\AboutImage\DeleteAction;
use App\Action\AboutImage\IndexAction;
use App\Action\AboutImage\ShowAction;
use App\Action\AboutImage\SortAction;
use App\Action\AboutImage\UpdateAction;
use App\Dto\AboutImage\CreateDto;
use App\Dto\AboutImage\QueryDto;
use App\Dto\AboutImage\SortDto;
use App\Dto\AboutImage\UpdateDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AboutImageController extends BaseController implements HasMiddleware
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
        return $this->sendResponse($action($dto), "About Image successfully sorted");
    }

    public function store(CreateDto $dto, CreateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "About Image successfully created");
    }

    public function show(int $id, ShowAction $action): JsonResponse
    {
        return $this->sendResponse($action($id));
    }

    public function update(UpdateDto $dto, UpdateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "About Image successfully updated");
    }

    public function destroy(int $id, DeleteAction $action): JsonResponse
    {
        return $this->sendResponse($action($id), "About Image successfully deleted");
    }
}
