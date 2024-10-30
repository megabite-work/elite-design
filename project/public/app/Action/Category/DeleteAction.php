<?php

namespace App\Action\Category;

use App\Exception\ErrorException;
use App\Models\Category;

final class DeleteAction
{
    public function __invoke(int $id): array
    {
        $category = Category::find($id);

        if (!$category) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        $category->delete();

        return [];
    }
}
