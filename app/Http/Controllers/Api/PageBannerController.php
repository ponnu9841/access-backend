<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PagesBanner;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageBannerController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getBanner']]);
        $this->folderName = 'pagesBanner';
    }
    public function getBanner(){
        $pagesBanner = PagesBanner::orderBy('created_at', 'desc')->get();

        return response([
            'data' => $pagesBanner
        ]);
    }
    public function createBanner(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'alt' => 'nullable|string',
            'title' => 'nullable|string',
            'page' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $banner = new PagesBanner();
        $banner->title = $request->title;
        $banner->alt = $request->alt;
        $banner->page = $request->page;

        if ($request->hasFile('image')) {
            $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
            $banner->image = $uploadedPath;
        }

        $banner->save();
        return response([
            'message' => 'Banner Added Successfully',
            'data' => $banner
        ]);
    }
    public function updateBanner(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'alt' => 'nullable|string',
            'title' => 'nullable|string',
            'page' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $banner = PagesBanner::find($request->id);
        $banner->title = $request->title;
        $banner->alt = $request->alt;
        $banner->page = $request->page;

        if ($request->hasFile('image')) {
            $this->fileService->deleteFile($banner->image, $this->folderName);
            $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
            $banner->image = $uploadedPath;
        }

        $banner->save();
        return response([
            'message' => 'Banner Updated Successfully',
            'data' => $banner
        ]);
    }
}
