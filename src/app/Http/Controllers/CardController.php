<?php

namespace App\Http\Controllers;

use App\Http\Requests\Card\CreateRequest;
use App\Services\Card\CreateService;
use Auth;

class CardController extends Controller
{
    //
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
}
