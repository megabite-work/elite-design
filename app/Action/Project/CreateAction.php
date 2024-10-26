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
        foreach ($data['files'] as $key => $file) {
            $data['files'][$key]['file'] = MediaObject::upload($file, 'files');
            $data['files'][$key]['alt'] = !empty($data['file_alt'][$key]) ? $data['file_alt'][$key] : "";
        }
        ksort($data['files']);

        foreach ($data['pictures'] as $key => $picture) {
            $data['pictures'][$key]['picture'] = MediaObject::upload($picture);
            $data['pictures'][$key]['alt'] = !empty($data['picture_alt'][$key]) ? $data['picture_alt'][$key] : null;
        }
        ksort($data['pictures']);

        foreach ($data['plan_photos'] as $key => $plan_photo) {
            $data['plan_photos'][$key]['plan_photo'] = MediaObject::upload($plan_photo);
            $data['plan_photos'][$key]['alt'] = !empty($data['plan_photo_alt'][$key]) ? $data['plan_photo_alt'][$key] : null;
        }
        ksort($data['plan_photos']);

        if (!empty($data['image'])) {
            $data['image'] = MediaObject::upload($data['image']);
        }
        unset($data['file_alt'], $data['picture_alt'], $data['plan_photo_alt']);

        return $data;
    }
}
