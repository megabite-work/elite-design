<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Http\Controllers\API\BaseController;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }
    public function index()
    {
        $projects = Project::get();

        return $this->sendResponse($projects);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'required', 'string', 'max:255'],
            'short_description' => ['bail', 'required', 'string'],
            'city' => ['bail', 'required', 'string', 'max:255'],
            'year' => ['bail', 'required', 'integer'],
            'description' => ['bail', 'required', 'string'],
            'files' => ['bail', 'required', 'array'],
            'files.*' => ['bail', 'required', 'file', 'max:10240'],
            'image' => ['bail', 'required', 'image', 'max:10240'],
            'pictures' => ['bail', 'required', 'array'],
            'pictures.*' => ['bail', 'required', 'image', 'max:10240'],
            'characteristics' => ['bail', 'required', 'string'],
            'plans' => ['bail', 'required', 'string'],
            'plan_photos' => ['bail', 'required', 'array'],
            'plan_photos.*' => ['bail', 'required', 'image', 'max:10240'],
            'video' => ['bail', 'required', 'string'],
            'address' => ['bail', 'required', 'string'],
            'longitude' => ['bail', 'required', 'string'],
            'latitude' => ['bail', 'required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['title', 'short_description', 'city', 'year', 'description', 'files', 'image', 'pictures', 'characteristics', 'plans', 'plan_photos', 'video', 'address', 'longitude', 'latitude']);

        foreach ($data['files'] as $key => $file) {
            $data['files'][$key] = MediaObject::upload($file, 'files');
        }
        ksort($data['files']);

        foreach ($request->pictures as $key => $picture) {
            $data['pictures'][$key] = MediaObject::upload($picture);
        }
        ksort($data['pictures']);

        foreach ($request->plan_photos as $key => $plan_photo) {
            $data['plan_photos'][$key] = MediaObject::upload($plan_photo);
        }
        ksort($data['plan_photos']);

        if (!empty($data['image'])) {
            $data['image'] = MediaObject::upload($data['image']);
        }

        $project = Project::create($data);

        return $this->sendResponse($project, "Project successfully created");
    }

    public function show(int $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        return $this->sendResponse($project);
    }

    public function update(Request $request, int $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'nullable', 'string', 'max:255'],
            'short_description' => ['bail', 'nullable', 'string'],
            'city' => ['bail', 'nullable', 'string', 'max:255'],
            'year' => ['bail', 'nullable', 'integer'],
            'description' => ['bail', 'nullable', 'string'],
            'files' => ['bail', 'nullable', 'array'],
            'files.*' => ['bail', 'nullable', 'file', 'max:10240'],
            'image' => ['bail', 'nullable', 'image', 'max:10240'],
            'pictures' => ['bail', 'nullable', 'array'],
            'pictures.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'characteristics' => ['bail', 'nullable', 'string'],
            'plans' => ['bail', 'nullable', 'string'],
            'plan_photos' => ['bail', 'nullable', 'array'],
            'plan_photos.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'video' => ['bail', 'nullable', 'string'],
            'address' => ['bail', 'nullable', 'string'],
            'longitude' => ['bail', 'nullable', 'string'],
            'latitude' => ['bail', 'nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['title', 'short_description', 'city', 'year', 'description', 'files', 'image', 'pictures', 'characteristics', 'plans', 'plan_photos', 'video', 'address', 'longitude', 'latitude']));
        $deleted_files = [];

        if (!empty($data['files'])) {
            $files = $project->files ?? [];
            foreach ($files as $key => $file) {
                if (empty($data['files'][$key])) {
                    $data['files'][$key] = $file;
                } else {
                    if (Storage::disk('public')->exists($file)) $deleted_files[] = $file;

                    $data['files'][$key] = MediaObject::upload($data['files'][$key], 'files');
                }
            }
            ksort($data['files']);
        }

        if (!empty($data['pictures'])) {
            $pictures = $project->pictures ?? [];
            foreach ($pictures as $key => $picture) {
                if (empty($data['pictures'][$key])) {
                    $data['pictures'][$key] = $picture;
                } else {
                    if (Storage::disk('public')->exists($picture)) $deleted_files[] = $picture;

                    $data['pictures'][$key] = MediaObject::upload($data['pictures'][$key]);
                }
            }
            ksort($data['pictures']);
        }

        if (!empty($data['plan_photos'])) {
            $plan_photos = $project->plan_photos ?? [];
            foreach ($plan_photos as $key => $plan_photo) {
                if (empty($data['plan_photos'][$key])) {
                    $data['plan_photos'][$key] = $plan_photo;
                } else {
                    if (Storage::disk('public')->exists($plan_photo)) $deleted_files[] = $plan_photo;

                    $data['plan_photos'][$key] = MediaObject::upload($data['plan_photos'][$key]);
                }
            }
            ksort($data['plan_photos']);
        }

        if (!empty($data['image'])) {
            $image = $project->image ?? null;
            if (Storage::disk('public')->exists($image)) $deleted_files[] = $image;
            $data['image'] = MediaObject::upload($data['image']);
        }

        $project->update($data);

        Storage::disk('public')->delete($deleted_files);

        return $this->sendResponse($project, "Project successfully updated");
    }

    public function destroy(int $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return $this->sendError('Not Found', ['error' => 'Not Found']);
        }

        $project->delete();

        return $this->sendResponse([], "Project successfully deleted");
    }
}
