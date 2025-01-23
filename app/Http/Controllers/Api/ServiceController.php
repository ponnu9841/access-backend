<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getService']]);
        $this->folderName = 'services';
    }

    public function getService(request $request)
    {
        $service = Service::orderBy('created_at', 'desc')->get();

        return response([
            'data' => $service
        ]);
    }

    public function createService(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'alt' => 'string',
                'short_description' => 'string',
                'long_description' => 'string',
            ]);
            if (!$validator->passes()) {
                return response([
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 404);
            }
            $service = new Service();
            $service->title = $request->title;
            $service->alt = $request->alt;
            $service->short_description = htmlspecialchars($request->shortDescription, ENT_QUOTES);
            $service->long_description = htmlspecialchars($request->longDescription, ENT_QUOTES);

            if ($request->hasFile('image')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $service->image = $uploadedPath;
            }

            $service->save();


            return response([
                'message' => 'Partner Added Successfully',
                'data' => $service,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }

    public function deleteService(Request $request)
    {
        try {
            $id = $request->id;
            $image = $request->image;
            Service::where('id', $id)->delete();

            $this->fileService->deleteFile($image, $this->folderName);

            return response([
                'message' => 'Service Deleted Successfully'
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
