<?php

namespace App\Http\Controllers\API;

use App\Helper\MediaObject;
use App\Http\Controllers\API\BaseController;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
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
    public function index(): JsonResponse
    {
        $projects = Project::get();

        return $this->sendResponse($projects);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['bail', 'required', 'array'],
            'title.*' => ['bail', 'required', 'string', 'max:255'],
            'short_description' => ['bail', 'required', 'array'],
            'short_description.*' => ['bail', 'required', 'string', 'max:255'],
            'city' => ['bail', 'required', 'array'],
            'city.*' => ['bail', 'required', 'string', 'max:255'],
            'year' => ['bail', 'required', 'integer'],
            'description' => ['bail', 'required', 'array'],
            'description.*' => ['bail', 'required', 'string'],
            'files' => ['bail', 'required', 'array'],
            'files.*' => ['bail', 'required', 'file', 'max:10240'],
            'file_alt' => ['bail', 'nullable', 'array'],
            'file_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'file_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'image' => ['bail', 'required', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'pictures' => ['bail', 'required', 'array'],
            'pictures.*' => ['bail', 'required', 'image', 'max:10240'],
            'picture_alt' => ['bail', 'nullable', 'array'],
            'picture_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'picture_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'characteristics' => ['bail', 'required', 'array'],
            'characteristics.*' => ['bail', 'required', 'string'],
            'plans' => ['bail', 'required', 'array'],
            'plans.*' => ['bail', 'required', 'string'],
            'plan_photos' => ['bail', 'required', 'array'],
            'plan_photos.*' => ['bail', 'required', 'image', 'max:10240'],
            'plan_photo_alt' => ['bail', 'nullable', 'array'],
            'plan_photo_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'plan_photo_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'video' => ['bail', 'required', 'string'],
            'address' => ['bail', 'required', 'string'],
            'longitude' => ['bail', 'required', 'string'],
            'latitude' => ['bail', 'required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['title', 'short_description', 'city', 'year', 'description', 'file_alt', 'alt', 'picture_alt', 'characteristics', 'plans', 'plan_photo_alt', 'video', 'address', 'longitude', 'latitude']);

        foreach ($request->file('files') as $key => $file) {
            $data['files'][$key]['file'] = MediaObject::upload($file, 'files');
            $data['files'][$key]['alt'] = !empty($data['file_alt'][$key]) ? $data['file_alt'][$key] : null;
        }
        ksort($data['files']);

        foreach ($request->file('pictures') as $key => $picture) {
            $data['pictures'][$key]['picture'] = MediaObject::upload($picture);
            $data['pictures'][$key]['alt'] = !empty($data['picture_alt'][$key]) ? $data['picture_alt'][$key] : null;
        }
        ksort($data['pictures']);

        foreach ($request->file('plan_photos') as $key => $plan_photo) {
            $data['plan_photos'][$key]['plan_photo'] = MediaObject::upload($plan_photo);
            $data['plan_photos'][$key]['alt'] = !empty($data['plan_photo_alt'][$key]) ? $data['plan_photo_alt'][$key] : null;
        }
        ksort($data['plan_photos']);

        if (!empty($request->file('image'))) {
            $data['image'] = MediaObject::upload($request->file('image'));
        }
        unset($data['file_alt'], $data['picture_alt'], $data['plan_photo_alt']);

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
            'title' => ['bail', 'nullable', 'array'],
            'title.*' => ['bail', 'nullable', 'string', 'max:255'],
            'short_description' => ['bail', 'nullable', 'array'],
            'short_description.*' => ['bail', 'nullable', 'string', 'max:255'],
            'city' => ['bail', 'nullable', 'array'],
            'city.*' => ['bail', 'nullable', 'string', 'max:255'],
            'year' => ['bail', 'nullable', 'integer'],
            'description' => ['bail', 'nullable', 'array'],
            'description.*' => ['bail', 'nullable', 'string'],
            'files' => ['bail', 'nullable', 'array'],
            'files.*' => ['bail', 'nullable', 'file', 'max:10240'],
            'file_alt' => ['bail', 'nullable', 'array'],
            'file_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'file_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'image' => ['bail', 'nullable', 'image', 'max:10240'],
            'alt' => ['bail', 'nullable', 'array', 'max:2'],
            'alt.*' => ['bail', 'nullable', 'string', 'max:255'],
            'pictures' => ['bail', 'nullable', 'array'],
            'pictures.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'picture_alt' => ['bail', 'nullable', 'array'],
            'picture_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'picture_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'characteristics' => ['bail', 'nullable', 'array'],
            'characteristics.*' => ['bail', 'nullable', 'string'],
            'plans' => ['bail', 'nullable', 'array'],
            'plans.*' => ['bail', 'nullable', 'string'],
            'plan_photos' => ['bail', 'nullable', 'array'],
            'plan_photos.*' => ['bail', 'nullable', 'image', 'max:10240'],
            'plan_photo_alt' => ['bail', 'nullable', 'array'],
            'plan_photo_alt.*' => ['bail', 'nullable', 'array', 'max:2'],
            'plan_photo_alt.*.*' => ['bail', 'nullable', 'string', 'max:255'],
            'video' => ['bail', 'nullable', 'string'],
            'address' => ['bail', 'nullable', 'string'],
            'longitude' => ['bail', 'nullable', 'string'],
            'latitude' => ['bail', 'nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = array_filter($request->only(['title', 'short_description', 'city', 'year', 'description', 'file_alt', 'alt', 'picture_alt', 'characteristics', 'plans', 'plan_photo_alt', 'video', 'address', 'longitude', 'latitude']));
        $deleted_files = [];
        foreach ($project->files as $key => $file) {
            if (!empty($request->file('files')[$key])) {
                $data['files'][$key]['file'] = MediaObject::upload($request->file('files')[$key], 'files');
                if (Storage::disk('public')->exists($file->file)) $deleted_files[] = $file->file;
            } else {
                $data['files'][$key]['file'] = $file->file;
            }
            $data['files'][$key]['alt']['en'] = (!empty($data['file_alt'][$key]) && !empty($data['file_alt'][$key]['en'])) ? $data['file_alt'][$key]['en'] : (!empty($file->alt->en) ? $file->alt->en : "");
            $data['files'][$key]['alt']['ru'] = (!empty($data['file_alt'][$key]) && !empty($data['file_alt'][$key]['ru'])) ? $data['file_alt'][$key]['ru'] : (!empty($file->alt->ru) ? $file->alt->ru : "");
        }
        ksort($data['files']);

        foreach ($project->pictures as $key => $picture) {
            if (!empty($request->file('pictures')[$key])) {
                $data['pictures'][$key]['picture'] = MediaObject::upload($request->file('pictures')[$key]);
                if (Storage::disk('public')->exists($picture->picture)) $deleted_files[] = $picture->picture;
            } else {
                $data['pictures'][$key]['picture'] = $picture->picture;
            }

            $data['pictures'][$key]['alt']['en'] = !empty($data['picture_alt'][$key]['en']) ? $data['picture_alt'][$key]['en'] : $picture->alt->en ?? "";
            $data['pictures'][$key]['alt']['ru'] = !empty($data['picture_alt'][$key]['ru']) ? $data['picture_alt'][$key]['ru'] : $picture->alt->ru ?? "";
        }
        ksort($data['pictures']);

        foreach ($project->plan_photos as $key => $plan_photo) {
            if (!empty($request->file('plan_photos')[$key])) {
                $data['plan_photos'][$key]['plan_photo'] = MediaObject::upload($request->file('plan_photos')[$key]);
                if (Storage::disk('public')->exists($plan_photo->plan_photo)) $deleted_files[] = $plan_photo->plan_photo;
            } else {
                $data['plan_photos'][$key]['plan_photo'] = $plan_photo->plan_photo;
            }

            $data['plan_photos'][$key]['alt']['en'] = !empty($data['plan_photo_alt'][$key]['en']) ? $data['plan_photo_alt'][$key]['en'] : $plan_photo->alt->en ?? "";
            $data['plan_photos'][$key]['alt']['ru'] = !empty($data['plan_photo_alt'][$key]['ru']) ? $data['plan_photo_alt'][$key]['ru'] : $plan_photo->alt->ru ?? "";
        }
        ksort($data['plan_photos']);

        if (!empty($request->file('image'))) {
            $data['image'] = MediaObject::upload($request->file('image'));
            if (Storage::disk('public')->exists($project->image)) $deleted_files[] = $project->image;
        }
        if (!empty($data['alt'])) {
            $data['alt']['ru'] = !empty($data['alt']['ru']) ? $data['alt']['ru'] : $project->alt->ru ?? "";
            $data['alt']['en'] = !empty($data['alt']['en']) ? $data['alt']['en'] : $project->alt->en ?? "";
        }

        $data['description']['en'] = !empty($data['description']['en']) ? $data['description']['en'] : $webSetting->description->en ?? "";
        $data['description']['ru'] = !empty($data['description']['ru']) ? $data['description']['ru'] : $webSetting->description->ru ?? "";
        $data['title']['en'] = !empty($data['title']['en']) ? $data['title']['en'] : $webSetting->title->en ?? "";
        $data['title']['ru'] = !empty($data['title']['ru']) ? $data['title']['ru'] : $webSetting->title->ru ?? "";
        $data['short_description']['en'] = !empty($data['short_description']['en']) ? $data['short_description']['en'] : $webSetting->short_description->en ?? "";
        $data['short_description']['ru'] = !empty($data['short_description']['ru']) ? $data['short_description']['ru'] : $webSetting->short_description->ru ?? "";
        $data['city']['en'] = !empty($data['city']['en']) ? $data['city']['en'] : $webSetting->city->en ?? "";
        $data['city']['ru'] = !empty($data['city']['ru']) ? $data['city']['ru'] : $webSetting->city->ru ?? "";
        $data['characteristics']['en'] = !empty($data['characteristics']['en']) ? $data['characteristics']['en'] : $webSetting->characteristics->en ?? "";
        $data['characteristics']['ru'] = !empty($data['characteristics']['ru']) ? $data['characteristics']['ru'] : $webSetting->characteristics->ru ?? "";
        $data['plans']['en'] = !empty($data['plans']['en']) ? $data['plans']['en'] : $webSetting->plans->en ?? "";
        $data['plans']['ru'] = !empty($data['plans']['ru']) ? $data['plans']['ru'] : $webSetting->plans->ru ?? "";
        unset($data['file_alt'], $data['picture_alt'], $data['plan_photo_alt']);

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

        $deleted_files = [];

        foreach ($project->files as $file) {
            if (Storage::disk('public')->exists($file->file)) $deleted_files[] = $file->file;
        }
        foreach ($project->pictures as $picture) {
            if (Storage::disk('public')->exists($picture->picture)) $deleted_files[] = $picture->picture;
        }
        foreach ($project->plan_photos as $plan_photo) {
            if (Storage::disk('public')->exists($plan_photo->plan_photo)) $deleted_files[] = $plan_photo->plan_photo;
        }

        if (Storage::disk('public')->exists($project->image)) $deleted_files[] = $project->image;


        $project->delete();
        Storage::disk('public')->delete($deleted_files);

        return $this->sendResponse([], "Project successfully deleted");
    }
}
