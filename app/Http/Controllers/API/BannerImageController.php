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
            'images' => ['bail', 'required', 'array'],
            'images.*' => ['bail', 'required', 'image', 'max:10240'],
            'formats' => ['bail', 'required', 'array'],
            'formats.*' => ['bail', 'required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['category_id', 'alt']);
        foreach ($request->file('images') as $key => $image) {
            $data['images'][$key]['image'] = MediaObject::upload($image);
            $data['images'][$key]['format'] = !empty($request->formats[$key]) ? $request->formats[$key] : "";
        }
        ksort($data['images']);
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
            'images' => ['bail', 'nullable', 'array'],
            'images.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'formats' => ['bail', 'nullable', 'array'],
            'formats.*' => ['bail', 'nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['category_id', 'alt']));

        $deleted_files = [];
        foreach ($request->file('images') as $key => $image) {
            dd($bannerImage->images[$key], $bannerImage->images[$key]->image);
            if (!empty($bannerImage->images[$key]->image)) {
                if (Storage::disk('public')->exists($bannerImage->images[$key]->image)) $deleted_files[] = $bannerImage->images[$key]->image;
            }

            $data['images'][$key]['image'] = MediaObject::upload($request->file('images')[$key]);
            $data['images'][$key]['format'] = !empty($request->formats[$key]) ? $request->formats[$key] : $image->format ?? "";
        }
        foreach ($bannerImage->images as $key => $image) {
            if (empty($data['images'][$key]) || empty($data['images'][$key]['image'])) {
                $data['images'][$key]['image'] = $image->image;
                $data['images'][$key]['format'] = $image->format ?? "";
            }
        }
        ksort($data['images']);
        
        if (!empty($data['alt'])) {
            $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $bannerImage->alt->ru ?? "";
            $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $bannerImage->alt->en ?? "";
        }

        $bannerImage->update($data);

        if ($deleted_files) Storage::disk('public')->delete($deleted_files);

        return $this->sendResponse($bannerImage, "Banner Image successfully updated");
    }

    public function destroy(int $id)
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $deleted_images = array_map(fn($image) => $image->image, $bannerImage->images);

        $bannerImage->delete();
        Storage::disk('public')->delete($deleted_images);

        return $this->sendResponse([], "Banner Image successfully deleted");
    }
}
