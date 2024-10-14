<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index']),
        ];
    }

    public function index()
    {
        $categories = Category::with('bannerImages')->orderBy('sort', 'ASC')->get();

        return $this->sendResponse($categories);
    }

    public function sort(Request $request)
    {
        $items = $request->items;

        foreach ($items as $item) {
            $category = Category::find($item['id']);
            $category->sort = $item['sort'];
            $category->save();
        }

        return $this->sendResponse([], "Category successfully sorted");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'string', 'unique:categories,name'],
            'sort' => ['bail', 'required', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['name', 'sort']);

        $category = Category::create($data);

        return $this->sendResponse($category, "Category successfully created");
    }

    public function show(int $id)
    {
        $category = Category::with('bannerImages')->find($id);

        if (!$category) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($category);
    }

    public function update(Request $request, int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'nullable', 'string', "unique:categories,name,exists,$category->id"],
            'sort' => ['bail', 'nullable', 'integer'],
        ]);
        
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        $category->update($request->all());

        return $this->sendResponse($category, "Category successfully updated");
    }

    public function destroy(int $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $category->delete();

        return $this->sendResponse([], "Category successfully deleted");
    }
}
