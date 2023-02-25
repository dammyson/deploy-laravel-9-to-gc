<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use App\Http\Requests\Profile\UpdateRequest;
use App\Services\Profile\UpdateService;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Services\Profile\UpdatePassword;

class ProfileController extends Controller
{
    // use CompanyIdTrait;

    public function __construct()
    {
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
    
        try {
            (new UpdateService($user, $validated))->run();
            $resource = new UserResource(User::find($user->id));
            return response()->json(['status' => true, 'data' => $resource, 'message' => 'profile update successful'], 200);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['status' => false,  'message' => 'Error processing request'], 500);
        }
    }


    public function get()
    {
        $user = Auth::user();
       
        try {
           // $resource = new UserResource(User::find($user->id));
            $resource = User::find($user->id);
            return response()->json(['status' => true, 'data' => $resource, 'message' => 'profile  details'], 200);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['status' => false,  'message' => 'Error processing request'], 500);
        }
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = \Auth::user();
        try {
            (new UpdatePassword($user, $validated))->run();
            $resource = new UserResource($user);
            return response()->json(['status' => true, 'data' => $resource, 'message' => 'profile update successful'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false,  'message' => 'Error processing request'], 500);
        }
    }

}
