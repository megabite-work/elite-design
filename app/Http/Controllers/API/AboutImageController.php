<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Models\AboutImage;
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
    public function index()
    {
        $aboutImages = AboutImage::orderBy('sort', 'ASC')->get();

        return $this->sendResponse($aboutImages);
    }

    public function sort(Request $request)
    {
        $items = $request->items;

        foreach ($items as $item) {
            $aboutImage = AboutImage::find($item['id']);
            $aboutImage->sort = $item['sort'];
            $aboutImage->save();
        }

        return $this->sendResponse([], "About Image successfully sorted");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'required', 'string', 'max:255'],
            'image' => ['bail', 'required', 'image', 'max:10240'],
            'alt' => ['bail', 'required', 'string', 'max:255'],
            'description' => ['bail', 'required', 'string'],
            'sort' => ['bail', 'required', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['title', 'alt', 'sort', 'image', 'description']);
        $data['image'] = MediaObject::upload($data['image']);

        $aboutImage = AboutImage::create($data);

        return $this->sendResponse($aboutImage, "About Image successfully created");
    }

    public function show(int $id)
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($aboutImage);
    }

    public function update(Request $request, int $id)
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'nullable', 'string', 'max:255'],
            'image' => ['bail', 'nullable', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'string', 'max:255'],
            'description' => ['bail', 'nullable', 'string'],
            'sort' => ['bail', 'nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['title', 'alt', 'sort', 'image', 'description']));
        $oldImage = null;
        if ($request->hasFile('image')) {
            $data['image'] = MediaObject::upload($data['image']);
            $oldImage = $aboutImage->image;
        }
        
        $aboutImage->update($data);
        
        if ($oldImage && Storage::disk('public')->exists($oldImage)) Storage::disk('public')->delete($oldImage);

        return $this->sendResponse($aboutImage, "About Image successfully updated");
    }

    public function destroy(int $id)
    {
        $aboutImage = AboutImage::find($id);

        if (!$aboutImage) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $aboutImage->delete();

        return $this->sendResponse([], "About Image successfully deleted");
    }
}
