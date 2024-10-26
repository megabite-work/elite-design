<?php

namespace App\Action\Project;

use App\Dto\Project\CreateDto;
use App\Dto\Project\IndexDto;
use App\Helper\MediaObject;
use App\Models\Project;

final class CreateAction
{
    public function __invoke(CreateDto $dto): IndexDto
    {
        $data = $this->uploadMediaObject($dto->toArray());

        return IndexDto::from(Project::create($data));
    }

    private function uploadMediaObject(array $data): array
    {
        $files = [];
        foreach ($data['files'] as $key => $file) {
            $files[$key]['file'] = MediaObject::upload($file, 'files');
            $files[$key]['alt'] = !empty($data['file_alt'][$key]) ? $data['file_alt'][$key] : "";
        }
        $data['files'] = $files;
        ksort($data['files']);

        $pictures = [];
        foreach ($data['pictures'] as $key => $picture) {
            $pictures[$key]['picture'] = MediaObject::upload($picture);
            $pictures[$key]['alt'] = !empty($data['picture_alt'][$key]) ? $data['picture_alt'][$key] : null;
        }
        $data['pictures'] = $pictures;
        ksort($data['pictures']);

        $plan_photos = [];
        foreach ($data['plan_photos'] as $key => $plan_photo) {
            $plan_photos[$key]['plan_photo'] = MediaObject::upload($plan_photo);
            $plan_photos[$key]['alt'] = !empty($data['plan_photo_alt'][$key]) ? $data['plan_photo_alt'][$key] : null;
        }
        $data['plan_photos'] = $plan_photos;
        ksort($data['plan_photos']);

        if (!empty($data['image'])) {
            $data['image'] = MediaObject::upload($data['image']);
        }
        unset($data['file_alt'], $data['picture_alt'], $data['plan_photo_alt']);

        return $data;
    }
}
