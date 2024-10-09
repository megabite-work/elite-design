<?php

namespace App\Http\Controllers\API;

use App\Models\BannerImage;
use Illuminate\Http\Request;

class BannerImageController extends BaseController
{
    public function index(Request $request)
    {
        $bannerImages = BannerImage::all();

        return $this->sendResponse($bannerImages);
    }

    public function store(Request $request)
    {
        $bannerImage = BannerImage::create($request->all());

        return $this->sendResponse($bannerImage, "Banner Image successfully created");
    }

    public function show(BannerImage $bannerImage)
    {
        return $this->sendResponse($bannerImage);
    }

    public function update(Request $request, BannerImage $bannerImage)
    {
        $bannerImage->update($request->all());

        return $this->sendResponse($bannerImage, "Banner Image successfully updated");
    }

    public function destroy(BannerImage $bannerImage)
    {
        $bannerImage->delete();

        return $this->sendResponse([], "Banner Image successfully deleted");
    }
}
