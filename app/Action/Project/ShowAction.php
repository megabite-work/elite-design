<?php

namespace App\Action\Project;

use App\Dto\Project\IndexDto;
use App\Exception\ErrorException;
use App\Models\Project;

final class ShowAction
{
    public function __invoke(int $id): IndexDto
    {
        $project = Project::find($id);

        if (!$project) {
            throw new ErrorException('Not Found', ['error' => 'Not Found']);
        }

        return IndexDto::from($project);
    }
}
