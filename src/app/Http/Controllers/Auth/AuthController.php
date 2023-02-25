<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteRegRequest;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Http\Requests\Auth\CreateRequest;
use App\Http\Requests\Auth\CreateSmsRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Services\User\RegistrationService;
use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Services\User\ForgetPassword;
use App\Support\Enum\UserStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\Profile\UpdatePassword;
use App\Services\User\CompleteRegistrationService;
use App\Services\User\CreateOtpService;
use App\Services\User\SMSService;
use App\Support\Enum\ClassMessages;
use Illuminate\Http\Response;
use App\Services\User\GetUserPref;

class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     * @param UserRepository $users
     */

    public function create(CreateRequest $request)
    {
        $validated = $request->validated();
        try {
            $new_user = new RegistrationService($validated);
            $registered_user = $new_user->run();
            $token = $registered_user->createToken('paychange')->accessToken;
            $registered_user = User::with(['roles'])->findorfail($registered_user->id);
            return response()->json(['status' => true, 'data' => $registered_user , 'token' => $token,  'message' => 'registration successful'], 201);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['status' => false,  'message' => 'Error processing request'], 500);
        }
    }


    public function UpdateReg(CompleteRegRequest $request)
    {
        $validated = $request->validated();
        try {
            $new_user = new CompleteRegistrationService($validated);
            $registered_user = $new_user->run();
            $registered_user = User::findorfail($registered_user->id);
            return response()->json(['status' => true, 'data' => $registered_user , 'message' => 'registration successful'], 201);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['status' => false,  'message' => 'Error processing request'], 500);
        }
    }

      /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(LoginRequest $request)
    {
        $validated = $request->validated();
        try{
            $user = User::with(['roles'])->where('phone_number', $validated['phone_number'])->firstOrFail();
            if(!$user->status){
                throw new \Exception("User account".$user->user_status, 400);
            }
            $user->mobile_token = $request->mobile_token;
            $user->save();
        }catch(\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
        }

        $user['token'] = $user->createToken('paychange')->accessToken;
        return response()->json(['data' => $user], 200);

    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }



    public function SendSms(CreateSmsRequest $request)
    {
        $validated = $request->validated();
        try {
            $new_otp = new CreateOtpService($validated);
            $new_otp = $new_otp->run();
            $msg='Dear customer, use this One Time Password ' .  $new_otp->otp. ' to log in to your (House of Oni) account. This OTP will be valid for the next 2 hrs.';
            // $sms = new SMSService($msg, $new_otp->phone_number);
            // $new_sms = $sms ->run();
            return response()->json(['status' => true, 'data' => $new_otp, 'message' => 'sms sent successfully'], 201);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['status' => false,  'message' => $exception->getMessage()], 500);
        }
    }


    public function VerifySms(VerifyOtpRequest $request)
    {
        $validated = $request->validated();
        try {
            $model = Otp::where('phone_number', '=', $validated['phone_number']) 
            ->where('otp', '=', $validated['otp'])
            ->firstOrFail();
            return response()->json(['status' => true, 'data' => $model, 'message' => 'Verified successfully'], 201);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['status' => false,  'message' => $exception->getMessage()], 500);
        }
    }

}
