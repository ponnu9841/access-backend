<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getTags']]);
    }
    public function getTags(){
        $seoTags = Seo::orderBy('created_at', 'desc')->get();

        return response([
            'data' => $seoTags,
        ]);
    }
    public function createSeoTag(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'page' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $seo = new Seo();
        $seo->title = $request->title;
        $seo->description = $request->description;
        $seo->page = $request->page;
        $seo->save();
        return response([
            'message' => 'Seo Tags Added Successfully',
            'data' => $seo
        ]);
    }
    public function updateSeoTag(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'page' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $seo = Seo::find($request->id);
        $seo->title = $request->title;
        $seo->description = $request->description;
        $seo->page = $request->page;
        $seo->update();
        return response([
            'message' => 'Seo Tags Updated Successfully',
            'data' => $seo
        ]);
    }
}
