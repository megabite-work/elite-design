<?php

namespace App\Action\WebSetting;

use App\Dto\WebSetting\IndexDto;
use App\Dto\WebSetting\UpdateDto;
use App\Helper\MediaObject;
use App\Models\WebSetting;
use Illuminate\Support\Facades\Storage;

final class UpdateAction
{
    public function __invoke(UpdateDto $dto): IndexDto
    {
        $webSetting = WebSetting::find($dto->id);
        [$data, $deleted_files] = $this->updateMediaObject(array_filter($dto->toArray()), $webSetting);
        $data = $this->updateText($data, $webSetting);
        $webSetting->update($data);
        Storage::disk('public')->delete($deleted_files);

        return IndexDto::from($webSetting);
    }

    private function updateMediaObject(array $data, WebSetting $webSetting): array
    {
        $deleted_files = [];
        $images = array_values($webSetting->images);
        foreach ($data['images'] ?? [] as $key => $image) {
            if (!empty($images[$key]['image']) && Storage::disk('public')->exists($images[$key]['image'])) $deleted_files[] = $images[$key]['image'];

            $images[$key]['image'] = MediaObject::upload($image);
        }
        
        foreach ($data['alt'] ?? [] as $key => $value) {
            $images[$key]['alt']['en'] = !empty($value['en']) ? $value['en'] : $image->alt['en'] ?? "";
            $images[$key]['alt']['ru'] = !empty($value['ru']) ? $value['ru'] : $image->alt['ru'] ?? "";
        }
        ksort($data['images']);
        $data['images'] = array_values($images);
        unset($data['alt']);


        return [$data, $deleted_files];
    }

    private function updateText(array $data, WebSetting $webSetting): array
    {
        $data['about']['ru'] = !empty($data['about']['ru']) ? $data['about']['ru'] : $webSetting->about['ru'] ?? "";
        $data['about']['en'] = !empty($data['about']['en']) ? $data['about']['en'] : $webSetting->about['en'] ?? "";
        

        return $data;
    }
}
