<?php

namespace App\Action\WebSetting;

use App\Exception\ErrorException;
use App\Models\WebSetting;
use Illuminate\Support\Facades\Storage;

final class DeleteAction
{
    public function __invoke(int $id): array
    {
        $webSetting = WebSetting::find($id);

        if (!$webSetting) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        $deleted_files = [];
        foreach ($webSetting->images as $image) {
            if (Storage::disk('public')->exists($image['image'])) $deleted_files[] = $image['image'];
        }

        $webSetting->delete();
        Storage::disk('public')->delete($deleted_files);

        return [];
    }
}
