<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;

class TransactionPinController extends Controller
{
    public function create(Request $request) {
        $this->validate($request, [
            'pin' => 'required|between:4,10|confirmed'
        ]);

        $user = User::findorfail(Auth::id());
        $user->transaction_pin = Hash::make($request->pin);
        $user->save();

        return response()->json([
            'message' => 'Transaction pin successfully created'
        ], 201);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'old_pin' => 'required',
            'new_pin' => 'required|between:4,10|confirmed|different:old_pin'
        ]);

        $user = User::findorfail(Auth::id());

        if (Hash::check($request->old_pin, Auth::user()->transaction_pin) == false) {
            return response(['message' => 'Your Old Pin is incorrect'], 401);  
        } 

        $user->transaction_pin = Hash::make($request->new_pin);
        $user->save();

        return response([
            'message' => 'Transaction pin successfully updated'
        ], 201);
    }

    public function validatePin(Request $request)
    {
        $this->validate($request, [
            'transaction_pin' => 'required'
        ]);

        $user = User::findorfail(Auth::id());

        if (Hash::check($request->transaction_pin, Auth::user()->transaction_pin) == false) {
            return response(['message' => 'invalid pin'], 401);  
        } 
        return response(['message' => 'valid pin'], 201);
    }
}