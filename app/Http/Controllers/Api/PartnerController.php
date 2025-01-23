<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\FileDeleteService;

class PartnerController extends Controller
{
    protected $fileDeleteService;
    public function __construct(FileDeleteService $fileDeleteService)
    {
        $this->fileDeleteService = $fileDeleteService;
        $this->middleware('auth:api', ['except' => ['getPartner']]);
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

            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                $imageName = Str::random(20) . '_' . time() . '.' . $ext;

                $destPath = public_path() . '/uploads/partners/' . date("Y");

                $image->move($destPath, $imageName);
                $partner->image = asset('uploads/partners/' . date('Y') . '/' . $imageName);
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

            $this->fileDeleteService->deleteFile($image, 'partners');

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
