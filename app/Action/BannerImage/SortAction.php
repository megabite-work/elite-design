<?php

namespace App\Action\BannerImage;

use App\Dto\BannerImage\SortDto;
use App\Models\BannerImage;

final class SortAction
{
    public function __invoke(SortDto $dto): array
    {
        foreach ($dto->items as $item) {
            BannerImage::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return [];
    }
}
