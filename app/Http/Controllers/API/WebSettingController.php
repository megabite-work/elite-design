<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Http\Controllers\API\BaseController;
use App\Models\WebSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WebSettingController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
        ];
    }
    public function index(): JsonResponse
    {
        $webSettings = WebSetting::get();

        return $this->sendResponse($webSettings);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'about' => ['bail', 'required', 'array'],
            'about.*' => ['bail', 'required', 'string'],
            'images' => ['bail', 'required', 'array'],
            'images.*' => ['bail', 'required', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array'],
            'alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['about', 'alt']);
        foreach ($request->file('images') as $key => $image) {
            $data['images'][$key]['image'] = MediaObject::upload($image);
            $data['images'][$key]['alt'] = !empty($data['alt'][$key]) ? $data['alt'][$key] : null;
        }
        ksort($data['images']);
        unset($data['alt']);

        $webSetting = WebSetting::create($data);

        return $this->sendResponse($webSetting, "Web Setting successfully created");
    }

    public function show(int $id): JsonResponse
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($webSetting);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'about' => ['bail', 'nullable', 'array'],
            'about.*' => ['bail', 'nullable', 'string'],
            'images' => ['bail', 'nullable', 'array'],
            'images.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array'],
            'alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['about', 'alt']));
        $oldImages = [];
        foreach ($webSetting->images as $key => $image) {
            if (!empty($request->file('images')[$key])) {
                $data['images'][$key]['image'] = MediaObject::upload($request->file('images')[$key]);
                if (Storage::disk('public')->exists($image)) $oldImages[] = $image;
            } else {
                $data['images'][$key]['image'] = $image->image;
            }

            $data['images'][$key]['alt']['en'] = !empty($data['alt'][$key]['en']) ? $data['alt'][$key]['en'] : $image->alt->en ?? "";
            $data['images'][$key]['alt']['ru'] = !empty($data['alt'][$key]['ru']) ? $data['alt'][$key]['ru'] : $image->alt->ru ?? "";
        }
        $data['about']['en'] = !empty($data['about']['en']) ? $data['about']['en'] : $webSetting->about->en ?? "";
        $data['about']['ru'] = !empty($data['about']['ru']) ? $data['about']['ru'] : $webSetting->about->ru ?? "";
        ksort($data['images']);
        unset($data['alt']);

        $webSetting->update($data);

        Storage::disk('public')->delete($oldImages);

        return $this->sendResponse($webSetting, "Web Setting successfully updated");
    }

    public function destroy(int $id): JsonResponse
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        if (Storage::disk('public')->exists($webSetting->image)) Storage::disk('public')->delete($webSetting->image);

        $webSetting->delete();

        return $this->sendResponse([], "Web Setting successfully deleted");
    }
}
