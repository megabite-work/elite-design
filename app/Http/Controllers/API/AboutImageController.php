<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Models\AboutImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutImageController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
        ];
    }

    public function index(): JsonResponse
    {
        $aboutImages = AboutImage::orderBy('sort', 'ASC')->get();

        return $this->sendResponse($aboutImages);
    }

    public function sort(Request $request): JsonResponse
    {
        $items = $request->items;

        foreach ($items as $item) {
            $aboutImage = AboutImage::find($item['id']);
            $aboutImage->sort = $item['sort'];
            $aboutImage->save();
        }

        return $this->sendResponse([], "About Image successfully sorted");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'required', 'array'],
            'title.*' => ['bail', 'required', 'string', 'max:255'],
            'image' => ['bail', 'required', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'description' => ['bail', 'required', 'array'],
            'description.*' => ['bail', 'required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['title', 'alt', 'description']);
        $data['image'] = MediaObject::upload($request->file('image'));
        $data['sort'] = (AboutImage::latest()->first()->id ?? 0) + 1;

        $aboutImage = AboutImage::create($data);

        return $this->sendResponse($aboutImage, "About Image successfully created");
    }

    public function show(int $id): JsonResponse
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($aboutImage);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'nullable', 'array'],
            'title.*' => ['bail', 'nullable', 'string', 'max:255'],
            'image' => ['bail', 'nullable', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'description' => ['bail', 'nullable', 'array'],
            'description.*' => ['bail', 'nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['title', 'alt', 'description']));
        $oldImage = null;
        if ($request->hasFile('image')) {
            $data['image'] = MediaObject::upload($request->file('image'));
            $oldImage = $aboutImage->image;
        }
        if (!empty($data['alt'])) {
            $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $aboutImage->alt->ru ?? "";
            $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $aboutImage->alt->en ?? "";
        }

        $data['description']['en'] = !empty($data['description']['en']) ? $data['description']['en'] : $webSetting->description->en ?? "";
        $data['description']['ru'] = !empty($data['description']['ru']) ? $data['description']['ru'] : $webSetting->description->ru ?? "";
        $data['title']['en'] = !empty($data['title']['en']) ? $data['title']['en'] : $webSetting->title->en ?? "";
        $data['title']['ru'] = !empty($data['title']['ru']) ? $data['title']['ru'] : $webSetting->title->ru ?? "";

        $aboutImage->update($data);

        if ($oldImage && Storage::disk('public')->exists($oldImage)) Storage::disk('public')->delete($oldImage);

        return $this->sendResponse($aboutImage, "About Image successfully updated");
    }

    public function destroy(int $id): JsonResponse
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        if (Storage::disk('public')->exists($aboutImage->image)) Storage::disk('public')->delete($aboutImage->image);

        $aboutImage->delete();

        return $this->sendResponse([], "About Image successfully deleted");
    }
}
