<?php

namespace App\Action\AboutImage;

use App\Dto\AboutImage\SortDto;
use App\Models\AboutImage;

final class SortAction
{
    public function __invoke(SortDto $dto): array
    {
        foreach ($dto->items as $item) {
            AboutImage::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return [];
    }
}
