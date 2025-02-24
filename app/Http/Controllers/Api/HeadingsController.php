<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Heading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HeadingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getHeadings']]);
    }
    public function getHeadings(){
        $heading = Heading::orderBy('created_at', 'desc')->first();

        return response([
            'data' => $heading
        ]);
    }
    public function createHeading(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'string',
            'section' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $heading = new Heading();
        $heading->title = $request->title;
        $heading->description = $request->description;
        $heading->section = $request->section;

        $heading->save();
        return response([
            'message' => 'Heading Added Successfully',
            'data' => $heading
        ]);
    }
    public function updateHeading(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'title' => 'required|string',
            'description' => 'string',
            'section' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $heading = Heading::find($request->id);
        $heading->title = $request->title;
        $heading->description = $request->description;
        $heading->section = $request->section;

        $heading->save();
        return response([
            'message' => 'Heading Updated Successfully',
            'data' => $heading
        ]);
    }
}
