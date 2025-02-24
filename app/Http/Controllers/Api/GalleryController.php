<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getGallery']]);
        $this->folderName = 'gallery';
    }
    public function getGallery(request $request)
    {
        $pageSize = 9;
        if ($request->has('page_size')) {
            $pageSize = $request->page_size;
        }
        $gallery = Gallery::orderBy('created_at', 'desc')->paginate($pageSize);

        return response([
            'data' => $gallery
        ]);
    }

    public function createGallery(Request $request)
    {
        try {
            $gallery = new Gallery();
            $gallery->alt = $request->alt;
            $gallery->title = $request->title;
            $gallery->description = $request->description;
            $validator = Validator::make($request->all(), [
                'image' => 'required',
                'alt' => 'nullable|string',
                'title' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            if (!$validator->passes()) {
                return response([
                    'message' => "Invalid Request",
                    'errors' => $validator->errors()
                ], 404);
            }


            if ($request->hasFile('image')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $gallery->image = $uploadedPath;
            }

            $gallery->save();


            return response([
                'message' => 'Gallery Added Successfully',
                'data' => $gallery,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'error' => $th->getMessage(), // Include the error message
                'trace' => config('app.debug') ? $th->getTrace() : null, // Include trace only in debug mode
            ], 500);
        }
    }

    public function updateGallery(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                'alt' => 'nullable|string',
                'title' => 'nullable|string',
                'description' => 'string',
            ]);

            if (!$validator->passes()) {
                return response([
                    'message' => "Invalid Request",
                    'errors' => $validator->errors()
                ], 404);
            }

            $gallery = Gallery::find($request->id);
            $gallery->alt = $request->alt;
            $gallery->title = $request->title;
            $gallery->description = $request->description;

            if ($request->hasFile('image')) {
                $this->fileService->deleteFile($gallery->image, $this->folderName);
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $gallery->image = $uploadedPath;
            }

            $gallery->save();

            return response([
                'message' => 'Gallery Updated Successfully',
                'data' => $gallery,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'error' => $th->getMessage(), // Include the error message
                'trace' => config('app.debug') ? $th->getTrace() : null, // Include trace only in debug mode
            ], 500);
        }
    }

    public function deleteGallery(Request $request)
    {
        try {
            //code...
            $id = $request->id;
            $image = $request->image;
            Gallery::where('id', $id)->delete();

            $this->fileService->deleteFile($image, $this->folderName);

            return response([
                'message' => 'Gallery Deleted Successfully'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }
}
