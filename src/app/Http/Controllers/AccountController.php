<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\CreateRequest;
use App\Http\Requests\Account\TransferRequest;
use App\Services\Account\CreateService;
use App\Services\Account\ListService;
use App\Services\Account\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function create(CreateRequest $request)
    {
   
      
        $validated = $request->validated();
        $user = Auth::user();
        try {
            $new_card = new CreateService($validated, $user);
            $new_card = $new_card->run();
            return response()->json(['status' => true, 'data' => $new_card ,  'message' => 'registration successful'], 201);
        } catch (\Exception $exception) {
            return response()->json(['status' => false,  'error'=>$exception->getMessage(), 'message' => 'Error processing request'], 500);
        }
    }

    public function index(){
        $user = Auth::user();

        try {
            $new_card = new ListService($user);
            $new_card = $new_card->run();
            return response()->json(['status' => true, 'data' => $new_card ,  'message' => 'registration successful'], 201);
        } catch (\Exception $exception) {
            return response()->json(['status' => false,  'error'=>$exception->getMessage(), 'message' => 'Error processing request'], 500);
        }
    }

    public  function transfer(TransferRequest $request){
        $validated = $request->validated();
        $user = Auth::user();
        try {
            $new_card = new TransferService($validated,  $user);
            $new_card = $new_card->run();
            return response()->json(['status' => true, 'data' => $new_card ,  'message' => 'registration successful'], 201);
        } catch (\Exception $exception) {
            return response()->json(['status' => false,  'error'=>$exception->getMessage(), 'message' => 'Error processing request'], 500);
        }

    }
}
