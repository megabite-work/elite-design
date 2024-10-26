<?php

namespace App\Http\Controllers\API;

use App\Action\BannerImage\CreateAction;
use App\Action\BannerImage\DeleteAction;
use App\Action\BannerImage\IndexAction;
use App\Action\BannerImage\ShowAction;
use App\Action\BannerImage\SortAction;
use App\Action\BannerImage\UpdateAction;
use App\Dto\BannerImage\CreateDto;
use App\Dto\BannerImage\QueryDto;
use App\Dto\BannerImage\SortDto;
use App\Dto\BannerImage\UpdateDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BannerImageController extends BaseController implements HasMiddleware
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
        return $this->sendResponse($action($dto), "Banner Image successfully sorted");
    }

    public function store(CreateDto $dto, CreateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Banner Image successfully created");
    }

    public function show(int $id, ShowAction $action): JsonResponse
    {
        return $this->sendResponse($action($id));
    }

    public function update(UpdateDto $dto, UpdateAction $action): JsonResponse
    {
        return $this->sendResponse($action($dto), "Banner Image successfully updated");
    }

    public function destroy(int $id, DeleteAction $action): JsonResponse
    {
        return $this->sendResponse($action($id), "Banner Image successfully deleted");
    }
}
