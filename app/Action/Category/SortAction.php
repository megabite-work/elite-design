<?php

namespace App\Action\Category;

use App\Dto\Category\SortDto;
use App\Models\Category;

final class SortAction
{
    public function __invoke(SortDto $dto): array
    {
        foreach ($dto->items as $item) {
            Category::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return [];
    }
}
