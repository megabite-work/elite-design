<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Models\BannerImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerImageController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
        ];
    }
    public function index(): JsonResponse
    {
        $bannerImages = BannerImage::orderBy('sort', 'ASC')->get();

        return $this->sendResponse($bannerImages);
    }

    public function sort(Request $request): JsonResponse
    {
        $items = $request->items;

        foreach ($items as $item) {
            $bannerImage = BannerImage::find($item['id']);
            $bannerImage->sort = $item['sort'];
            $bannerImage->save();
        }

        return $this->sendResponse([], "Banner Image successfully sorted");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['bail', 'required', 'integer', 'exists:categories,id'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'image' => ['bail', 'required', 'image', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['category_id', 'alt']);
        $data['image'] = MediaObject::upload($request->file('image'));
        $data['sort'] = (BannerImage::latest()->first()->id ?? 0) + 1;

        $bannerImage = BannerImage::create($data);

        return $this->sendResponse($bannerImage, "Banner Image successfully created");
    }

    public function show(int $id): JsonResponse
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($bannerImage);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => ['bail', 'nullable', 'integer', 'exists:categories,id'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'image' => ['bail', 'nullable', 'image', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['category_id', 'alt']));
        $oldImage = null;
        if ($request->hasFile('image')) {
            $data['image'] = MediaObject::upload($request->file('image'));
            $oldImage = $bannerImage->image;
        }
        if (!empty($data['alt'])) {
            $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $bannerImage->alt->ru ?? "";
            $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $bannerImage->alt->en ?? "";
        }

        $bannerImage->update($data);

        if ($oldImage && Storage::disk('public')->exists($oldImage)) Storage::disk('public')->delete($oldImage);

        return $this->sendResponse($bannerImage, "Banner Image successfully updated");
    }

    public function destroy(int $id)
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        if (Storage::disk('public')->exists($bannerImage->image)) Storage::disk('public')->delete($bannerImage->image);

        $bannerImage->delete();

        return $this->sendResponse([], "Banner Image successfully deleted");
    }
}
