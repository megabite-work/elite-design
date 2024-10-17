<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Http\Controllers\API\BaseController;
use App\Models\WebSetting;
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
            new Middleware('auth:api'),
        ];
    }
    public function index()
    {
        $webSettings = WebSetting::get();

        return $this->sendResponse($webSettings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'about' => ['bail', 'required', 'string'],
            'images' => ['bail', 'required', 'array'],
            'images.*' => ['bail', 'required', 'image', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['about', 'images']);
        foreach ($request->images as $key => $image) {
            $data['images'][$key] = MediaObject::upload($image);
        }
        ksort($data['images']);

        $webSetting = WebSetting::create($data);

        return $this->sendResponse($webSetting, "Web Setting successfully created");
    }

    public function show(int $id)
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($webSetting);
    }

    public function update(Request $request, int $id)
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'about' => ['bail', 'nullable', 'string'],
            'images' => ['bail', 'nullable', 'array'],
            'images.*' => ['bail', 'nullable', 'image', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['about', 'images']));
        $oldImages = [];
        if (!empty($data['images'])) {
            $images = $webSetting->images ?? [];
            foreach ($images as $key => $image) {
                if (empty($data['images'][$key])) {
                    $data['images'][$key] = $image;
                } else {
                    $data['images'][$key] = MediaObject::upload($data['images'][$key]);
                    if (Storage::disk('public')->exists($image)) $oldImages[] = $image;
                }
            }
            ksort($data['images']);
        }

        $webSetting->update($data);

        Storage::disk('public')->delete($oldImages);

        return $this->sendResponse($webSetting, "Web Setting successfully updated");
    }

    public function destroy(int $id)
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $webSetting->delete();

        return $this->sendResponse([], "Web Setting successfully deleted");
    }
}
