<?php

namespace App\Action\Project;

use App\Exception\ErrorException;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

final class DeleteAction
{
    public function __invoke(int $id): array
    {
        $project = Project::find($id);

        if (!$project) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        $deleted_files = [];

        foreach ($project->files as $file) {
            if (Storage::disk('public')->exists($file['file'])) $deleted_files[] = $file['file'];
        }
        foreach ($project->pictures as $picture) {
            if (Storage::disk('public')->exists($picture['picture'])) $deleted_files[] = $picture['picture'];
        }
        foreach ($project->plan_photos as $plan_photo) {
            if (Storage::disk('public')->exists($plan_photo['plan_photo'])) $deleted_files[] = $plan_photo['plan_photo'];
        }

        if (Storage::disk('public')->exists($project->image)) $deleted_files[] = $project->image;

        $project->delete();
        Storage::disk('public')->delete($deleted_files);

        return [];
    }
}
