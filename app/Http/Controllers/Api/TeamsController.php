<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    protected $fileService;
    protected $folderName;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->middleware('auth:api', ['except' => ['getTeams']]);
        $this->folderName = 'teams';
    }

    public function getTeams()
    {
        $teams = Team::orderBy('created_at', 'desc')->get();
        $teams->transform(function ($team) {
            $team->designation = htmlspecialchars_decode($team->designation, ENT_QUOTES);
            return $team;
        });
        
        return response([
            'data' => $teams
        ]);
    }

    public function createTeam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
            ]);
            if (!$validator->passes()) {
                return response([
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 404);
            }
            $team = new Team();
            $team->name = $request->name;
            $team->alt = $request->alt;
            $team->designation = $request->designation;
            $team->linkedin_profile = $request->linkedInProfile;

            if ($request->hasFile('image')) {
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $team->image = $uploadedPath;
            }

            $team->save();

            return response([
                'message' => 'Team Member Added Successfully',
                'data' => $team,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }

    public function updateTeam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string',
                'name' => 'required|string|min:3',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                'designation' => 'string',
            ]);
            if (!$validator->passes()) {
                return response([
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 404);
            }
            $team = Team::find($request->id);
            $team->name = $request->name;
            $team->alt = $request->alt;
            $team->designation = $request->designation;
            $team->linkedin_profile = $request->linkedInProfile;

            if ($request->hasFile('image')) {
                $this->fileService->deleteFile($team->image, $this->folderName);
                $uploadedPath = $this->fileService->uploadFile($request->file('image'), $this->folderName);
                $team->image = $uploadedPath;
            }

            $team->save();

            return response([
                'message' => 'Team Member Updated Successfully',
                'data' => $team,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }

    public function deleteTeam(Request $request)
    {
        try {
            $id = $request->id;
            Team::where('id', $id)->delete();

            if ($request->image) {
                $this->fileService->deleteFile($request->image, $this->folderName);
            }

            return response([
                'message' => 'Team Member Deleted Successfully'
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
