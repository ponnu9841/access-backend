<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getTestimonial']]);
        $this->folderName = 'testimonials';
    }

    public function getTestimonial(request $request)
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();
        // Decode the descriptions
        $testimonials->transform(function ($testimonial) {
            $testimonial->testimonial = htmlspecialchars_decode($testimonial->testimonial, ENT_QUOTES);
            return $testimonial;
        });

        return response([
            'data' => $testimonials
        ]);
    }

    public function createTestimonial(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'url' => 'nullable|url|required_without:image',
                'image' => 'nullable|image|required_without:url',
                'name' => 'required|string|min:3',
                'testimonial' => 'required|string|min:3',
            ]);
            if (!$validator->passes()) {
                return response([
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 404);
            }
            $testimonial = new Testimonial();
            $testimonial->vido_url = $request->url;
            $testimonial->name = $request->name;
            $testimonial->designation = $request->designation;
            $testimonial->alt = $request->alt;
            $testimonial->testimonial = htmlspecialchars($request->testimonial, ENT_QUOTES);

            if ($request->hasFile('image')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $testimonial->image = $uploadedPath;
            }

            $testimonial->save();


            return response([
                'message' => 'Partner Added Successfully',
                'data' => $testimonial,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }

    public function deleteTestimonial(Request $request)
    {
        try {
            $id = $request->id;
            Testimonial::where('id', $id)->delete();

            if ($request->image) {
                $this->fileService->deleteFile($request->image, $this->folderName);
            }

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
