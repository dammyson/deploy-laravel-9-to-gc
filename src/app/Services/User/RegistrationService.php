<?php

namespace App\Services\User;


use App\Services\BaseServiceInterface;
use App\Models\User;

class RegistrationService implements BaseServiceInterface
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
        return  \DB::transaction(function () {
            $new_user = $this->createConfirmedUser($this->data);
            return $new_user;
        });
    }

    private function createConfirmedUser($data)
    {
        $user = $this->createUser($data);
        return $user;
    }

    private function createUser($data)
    {
        $user = new User();
        $user->id = uniqid();
        $user->phone_number = $data['phone_number'];
        $user->user_id = $this->generateUserId($data['user_type'], $data['phone_number']);
        $user->mobile_token = $data['mobile_token'];
        //remove when complete is implemented
        $user->status = "active";
        $user->save();
        $user->assignRole($data['user_type']);


        return $user;
    }

    public function generateUserId($user_type, $phone)
    {
        return (date('Y') % 2019).($user_type == 'CUSTOMER' ? 0 : 1).substr($phone, -4).substr(date('U'), -2);
    }
   
}
