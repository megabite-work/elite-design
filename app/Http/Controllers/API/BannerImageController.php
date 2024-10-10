<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Models\BannerImage;
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
            new Middleware('auth:api'),
        ];
    }
    public function index()
    {
        $bannerImages = BannerImage::orderBy('sort', 'ASC')->get();

        return $this->sendResponse($bannerImages);
    }

    public function sort(Request $request)
    {
        $items = $request->items;

        foreach ($items as $item) {
            $bannerImage = BannerImage::find($item['id']);
            $bannerImage->sort = $item['sort'];
            $bannerImage->save();
        }

        return $this->sendResponse([], "Banner Image successfully sorted");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['bail', 'required', 'integer', 'exists:categories,id'],
            'name' => ['bail', 'required', 'string', 'max:255'],
            'sort' => ['bail', 'required', 'integer'],
            'image' => ['bail', 'required', 'image', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['category_id', 'name', 'sort', 'image']);
        $data['image'] = MediaObject::upload($data['image']);

        $bannerImage = BannerImage::create($data);

        return $this->sendResponse($bannerImage, "Banner Image successfully created");
    }

    public function show(int $id)
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($bannerImage);
    }

    public function update(Request $request, int $id)
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => ['bail', 'nullable', 'integer', 'exists:categories,id'],
            'name' => ['bail', 'nullable', 'string', 'max:255'],
            'sort' => ['bail', 'nullable', 'integer'],
            'image' => ['bail', 'nullable', 'image', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($bannerImage->image)) Storage::disk('public')->delete($bannerImage->image);
            $data['image'] = MediaObject::upload($data['image']);
        }

        $bannerImage->update($data);

        return $this->sendResponse($bannerImage, "Banner Image successfully updated");
    }

    public function destroy(int $id)
    {
        $bannerImage = BannerImage::find($id);

        if (!$bannerImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $bannerImage->delete();

        return $this->sendResponse([], "Banner Image successfully deleted");
    }
}
