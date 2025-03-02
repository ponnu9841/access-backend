<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getPolicies']]);
    }
    public function getPolicies(){
        $policies = Policy::orderBy('created_at', 'desc')->get();

        return response([
            'data' => $policies,
        ]);
    }
    public function createPolicy(Request $request){
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'type' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $policy = new Policy();
        $policy->content = $request->content;
        $policy->type = $request->type;
        $policy->save();
        return response([
            'message' => 'Added Successfully',
            'data' => $policy
        ]);
    }
    public function updatePolicy(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'content' => 'required|string',
            'type' => 'required|string'
            
        ]);
        if (!$validator->passes()) {
            return response([
                'message' => 'Invalid Request',
                'errors' => $validator->errors()
            ], 404);
        }
        $policy = Policy::find($request->id);
        $policy->content = $request->content;
        $policy->type = $request->type;
        $policy->update();
        return response([
            'message' => 'Updated Successfully',
            'data' => $policy
        ]);
    }
}
