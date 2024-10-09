<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $categories = Category::all();

        return $this->sendResponse($categories);
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());

        return $this->sendResponse($category, "Category successfully created");
    }

    public function show(Category $category)
    {
        return $this->sendResponse($category);
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());

        return $this->sendResponse($category, "Category successfully updated");
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->sendResponse([], "Category successfully deleted");
    }
}
