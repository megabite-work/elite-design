<?php

namespace App\Http\Controllers\API;

use App\Mail\ContactMe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactMeController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['contactMe']),
        ];
    }

    public function contactMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'phone' => ['bail', 'required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->only(['name', 'phone']);
        Mail::queue(new ContactMe($data['name'], $data['phone']));

        return $this->sendResponse([], "Mail successfully pushed");
    }
}
