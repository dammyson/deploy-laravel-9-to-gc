<?php

namespace App\Services\User;

use App\Services\BaseServiceInterface;
use DB;
use App\Support\Enum\UserStatus;
use App\Models\User;
use \Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class CompleteService implements BaseServiceInterface
{
    protected $user;
    protected $data;


    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function run()
    {
        return $this->processCompleteRegistration();
    }

    private function processCompleteRegistration()
    {
        return  \DB::transaction(function () {
            $user = User::where('id', $this->user)->first();
            $user->first_name = $this->data['first_name'];
            $user->last_name = $this->data['last_name'];
            $user->password = $this->data['password'];
            $user->status = UserStatus::ACTIVE;
            $user->save();
            return $user;
        });
    }
}
