<?php

namespace App\Action\Project;

use App\Dto\Project\IndexDto;
use App\Dto\Project\UpdateDto;
use App\Helper\MediaObject;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

final class UpdateAction
{
    public function __invoke(UpdateDto $dto): IndexDto
    {
        $project = Project::find($dto->id);
        [$data, $deleted_files] = $this->updateMediaObject(array_filter($dto->toArray()), $project);
        $data = $this->updateText($data, $project);
        $project->update($data);

        Storage::disk('public')->delete($deleted_files);

        return IndexDto::from($project);
    }

    private function updateMediaObject(array $data, Project $project): array
    {
        $deleted_files = [];
        $files = $project->files;
        foreach ($data['files'] ?? [] as $key => $file) {
            if (Storage::disk('public')->exists($files[$key]['file'])) $deleted_files[] = $files[$key]['file'];

            $files[$key]['file'] = MediaObject::upload($file, 'files');
        }
        $data['files'] = $files;

        foreach ($data['file_alt'] ?? [] as $key => $value) {
            $data['files'][$key]['alt']['en'] = !empty($value['en']) ? $value['en'] : $files[$key]['alt']['en'] ?? "";
            $data['files'][$key]['alt']['ru'] = !empty($value['ru']) ? $value['ru'] : $files[$key]['alt']['ru'] ?? "";
        }
        ksort($data['files']);

        $pictures = $project->pictures;
        foreach ($data['pictures'] ?? [] as $key => $picture) {
            if (Storage::disk('public')->exists($pictures[$key]['picture'])) $deleted_files[] = $pictures[$key]['picture'];

            $pictures[$key]['picture'] = MediaObject::upload($picture);
        }
        $data['pictures'] = $pictures;

        foreach ($data['picture_alt'] ?? [] as $key => $value) {
            $data['pictures'][$key]['alt']['en'] = !empty($value['en']) ? $value['en'] : $pictures[$key]['alt']['en'] ?? "";
            $data['pictures'][$key]['alt']['ru'] = !empty($value['ru']) ? $value['ru'] : $pictures[$key]['alt']['ru'] ?? "";
        }
        ksort($data['pictures']);

        $plan_photos = $project->plan_photos;
        foreach ($data['plan_photos'] ?? [] as $key => $plan_photo) {
            if (Storage::disk('public')->exists($plan_photos[$key]['plan_photo'])) $deleted_files[] = $plan_photos[$key]['plan_photo'];

            $plan_photos[$key]['plan_photo'] = MediaObject::upload($plan_photo);
        }
        $data['plan_photos'] = $plan_photos;

        foreach ($data['picture_alt'] ?? [] as $key => $value) {
            $data['plan_photos'][$key]['alt']['en'] = !empty($value['en']) ? $value['en'] : $plan_photos[$key]['alt']['en'] ?? "";
            $data['plan_photos'][$key]['alt']['ru'] = !empty($value['ru']) ? $value['ru'] : $plan_photos[$key]['alt']['ru'] ?? "";
        }
        ksort($data['plan_photos']);

        if (!empty($data['image'])) {
            $data['image'] = MediaObject::upload($data['image']);
            if (Storage::disk('public')->exists($project->image)) $deleted_files[] = $project->image;
        } else {
            $data['image'] = $project->image;
        }

        return [$data, $deleted_files];
    }

    private function updateText(array $data, Project $project): array
    {
        $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $project->alt['ru'] ?? "";
        $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $project->alt['en'] ?? "";
        $data['description']['en'] = !empty($data['description']['en']) ? $data['description']['en'] : $project->description['en'] ?? "";
        $data['description']['ru'] = !empty($data['description']['ru']) ? $data['description']['ru'] : $project->description['ru'] ?? "";
        $data['title']['en'] = !empty($data['title']['en']) ? $data['title']['en'] : $project->title['en'] ?? "";
        $data['title']['ru'] = !empty($data['title']['ru']) ? $data['title']['ru'] : $project->title['ru'] ?? "";
        $data['short_description']['en'] = !empty($data['short_description']['en']) ? $data['short_description']['en'] : $project->short_description['en'] ?? "";
        $data['short_description']['ru'] = !empty($data['short_description']['ru']) ? $data['short_description']['ru'] : $project->short_description['ru'] ?? "";
        $data['city']['en'] = !empty($data['city']['en']) ? $data['city']['en'] : $project->city['en'] ?? "";
        $data['city']['ru'] = !empty($data['city']['ru']) ? $data['city']['ru'] : $project->city['ru'] ?? "";
        $data['characteristics']['en'] = !empty($data['characteristics']['en']) ? $data['characteristics']['en'] : $project->characteristics['en'] ?? "";
        $data['characteristics']['ru'] = !empty($data['characteristics']['ru']) ? $data['characteristics']['ru'] : $project->characteristics['ru'] ?? "";
        $data['plans']['en'] = !empty($data['plans']['en']) ? $data['plans']['en'] : $project->plans['en'] ?? "";
        $data['plans']['ru'] = !empty($data['plans']['ru']) ? $data['plans']['ru'] : $project->plans['ru'] ?? "";
        unset($data['file_alt'], $data['picture_alt'], $data['plan_photo_alt']);

        return $data;
    }
}
