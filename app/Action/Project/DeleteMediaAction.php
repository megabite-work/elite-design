<?php

namespace App\Action\Project;

use App\Dto\Project\DeleteMediaDto;
use App\Exception\ErrorException;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

final class DeleteMediaAction
{
    public function __invoke(DeleteMediaDto $dto): array
    {
        $project = Project::find($dto->id);

        if (!$project) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        $deleted_files = [];

        if (!is_null($dto->files) && !empty($project->files[$dto->files])) {
            if (Storage::disk('public')->exists($project->files[$dto->files]['file'])) $deleted_files[] = $project->files[$dto->files]['file'];
            $files = $project->files;
            unset($files[$dto->files]);
            $project->files = array_values($files);
        } else if (!is_null($dto->pictures) && !empty($project->pictures[$dto->pictures])) {
            if (Storage::disk('public')->exists($project->pictures[$dto->pictures]['picture'])) $deleted_files[] = $project->pictures[$dto->pictures]['picture'];
            $deleted_files[] = $project->pictures[$dto->pictures]['picture'];
            $pictures = $project->pictures;
            unset($pictures[$dto->pictures]);
            $project->pictures = array_values($pictures);
        } else if (!is_null($dto->plan_photos) && !empty($project->plan_photos[$dto->plan_photos])) {
            if (Storage::disk('public')->exists($project->plan_photos[$dto->plan_photos]['plan_photo'])) $deleted_files[] = $project->plan_photos[$dto->plan_photos]['plan_photo'];
            $deleted_files[] = $project->plan_photos[$dto->plan_photos]['plan_photo'];
            $plan_photos = $project->plan_photos;
            unset($plan_photos[$dto->plan_photos]);
            $project->plan_photos = array_values($plan_photos);
        }

        $project->save();
        Storage::disk('public')->delete($deleted_files);

        return [];
    }
}
