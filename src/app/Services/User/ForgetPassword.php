<?php

namespace App\Services\User;

use App\Models\Company;
use App\Models\Wallet;
use App\Notifications\User\ActivateUser;
use App\Notifications\User\ForgetPasword;
use App\Services\BaseServiceInterface;
use App\Services\Mail\InviteUserMailFormat;
use DB;
use App\Support\Enum\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class ForgetPassword implements BaseServiceInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function run()
    {
        return $this->processInvite();
    }

    private function processInvite()
    {
         $user = User::where('email',  $this->data['email'])->get();
         $user[0]->notify(new ForgetPasword($this->mailData($user[0])));
         return  $user;
    }

  

    private function mailData($invited_user)
    {
        $url = explode("change-password-verification", URL::temporarySignedRoute('forget.password_verification', now()->addHour(24), ['id'=>  $invited_user->id]));
        $front_url = env('CHANGE_PASSWORD_LANDING_LINK', 24).  $url[1];
            return [
                'companies' => 'BrandMobileAFrica',
                'recipient' =>   $invited_user->email,
                'subject' => "Invitation to join Vantage",
                'inviter' =>  "Welcome",
                'valid_duration' => "24 hours",
                'link' => $front_url
            ];
    }

}
