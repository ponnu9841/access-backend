<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getPartner']]);
        $this->folderName = 'partners';
    }
    public function getPartner(request $request)
    {
        $partner = Partner::orderBy('created_at', 'desc')->get();

        return response([
            'data' => $partner
        ]);
    }

    public function createPartner(Request $request)
    {
        try {
            $partner = new Partner();
            $partner->alt = $request->alt;
            $validator = Validator::make($request->all(), [
                'image' => 'required',
            ]);

            if (!$validator->passes()) {
                return response([
                    'message' => "Invalid Request",
                    'errors' => $validator->errors()
                ], 404);
            }

            if ($request->hasFile('image')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $partner->image = $uploadedPath;
            }

            $partner->save();


            return response([
                'message' => 'Partner Added Successfully',
                'data' => $partner,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }

    public function updatePartner(Request $request)
    {
        try {
            $partner = Partner::find($request->id);
            $partner->alt = $request->alt;
            $validator = Validator::make($request->all(), [
                'id' => 'required|string',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);

            if (!$validator->passes()) {
                return response([
                    'message' => "Invalid Request",
                    'errors' => $validator->errors()
                ], 404);
            }

            if ($request->hasFile('image')) {
                $this->fileService->deleteFile($partner->image, $this->folderName);
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $partner->image = $uploadedPath;
            }

            $partner->save();

            return response([
                'message' => 'Partner Added Successfully',
                'data' => $partner,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }

    public function deletePartner(Request $request)
    {
        try {
            //code...
            $id = $request->id;
            $image = $request->image;
            Partner::where('id', $id)->delete();

            $this->fileService->deleteFile($image, 'partners');

            return response([
                'message' => 'Partner Deleted Successfully'
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
