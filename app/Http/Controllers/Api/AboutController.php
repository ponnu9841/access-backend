<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getAbout']]);
        $this->folderName = 'about';
    }
    public function getAbout()
    {
        $about = About::orderBy('created_at', 'desc')->first();
        if ($about) {
            $about->short_description = htmlspecialchars_decode($about->short_description, ENT_QUOTES);
            $about->long_description = htmlspecialchars_decode($about->long_description, ENT_QUOTES);
        }

        return response([
            'data' => $about
        ]);
    }

    public function createAbout(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'subTitle' => 'string',
                'imageOne' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'imageTwo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'imageOneAlt' => 'string',
                'imageTwoAlt' => 'string',
                'shortDescription' => 'required|string',
                'longDescription' => 'string',
            ]);
            if (!$validator->passes()) {
                return response([
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 404);
            }
            $about = new About();
            $about->title = $request->title;
            $about->sub_title = $request->subTitle;
            $about->short_description = $request->shortDescription;
            $about->long_description = $request->longDescription;
            $about->image_one_alt = $request->imageOneAlt;
            $about->image_two_alt = $request->imageTwoAlt;

            if ($request->hasFile('imageOne')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('imageOne'), $this->folderName);
                $about->image_one = $uploadedPath;
            }
            if ($request->hasFile('imageTwo')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('imageTwo'), $this->folderName);
                $about->image_two = $uploadedPath;
            }

            $about->save();

            return response([
                'message' => 'About Added Successfully',
                'data' => $about,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function updateAbout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'subTitle' => 'required|string',
            'alt' => 'string',
            'shortDescription' => 'required|string',
            'longDescription' => 'string',
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        try {
            $about = About::find($request->id);
            $about->title = $request->title;
            $about->sub_title = $request->subTitle;
            $about->short_description = $request->shortDescription;
            $about->long_description = $request->longDescription;
            $about->image_one_alt = $request->imageOneAlt;
            $about->image_two_alt = $request->imageTwoAlt;

            if ($request->hasFile('imageOne')) {
                $this->fileService->deleteFile($about->image_one, $this->folderName);
                $uploadedPath = $this->fileService->uploadFile($request->file('imageOne'), $this->folderName);
                $about->image_one = $uploadedPath;
            }
            if ($request->hasFile('imageTwo')) {
                $this->fileService->deleteFile($about->image_two, $this->folderName);
                $uploadedPath = $this->fileService->uploadFile($request->file('imageTwo'), $this->folderName);
                $about->image_two = $uploadedPath;
            }

            $about->save();

            return response([
                'message' => 'About Added Successfully',
                'data' => $about,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
