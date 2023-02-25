<?php

namespace App\Services\User;

use App\Services\BaseServiceInterface;
use DB;
use App\Support\Enum\UserStatus;
use App\Models\User;
use App\Notifications\SendUserInvitationMail;
use App\Services\Mail\InviteUserMailFormat;

class InviteService implements BaseServiceInterface
{
    protected $user;
    protected $validated;
    protected $guard;
    protected $subject;

    public function __construct($validated, $user)
    {
        $this->validated = $validated;
        $this->user = $user;
    }

    public function run()
    {
        return $this->processInvite();
    }

    private function processInvite()
    {
        return  \DB::transaction(function () {
            $created_users =[];
            foreach ($this->validated as $data) {
                $roles = $data['roles'];
                $invited_user = $this->createUnconfirmedUser($roles, $this->user->companies->first()->id, $data['email'], 'web');
                // $invited_user->notify(new SendUserInvitationMail($this->mailData($invited_user, $this->user->first_name)));
                array_push($created_users, $invited_user);
               
            }
            return $created_users;
        });
    }

    private function createUnconfirmedUser($roles, $companies, $email, $guard)
    {
        $user = $this->createUser($email);
        $user->companies()->attach($companies);
        $user->assignRole($roles, $guard);
        return $user;
    }

    private function createUser($email)
    {
        $user = new User();
        $user->id = uniqid();
        $user->email = $email;
        $user->password = $email;
        $user->status = UserStatus::UNCONFIRMED;
        $user->save();
        return $user;
    }

    private function mailData($invited_user, $inviter_name)
    {
        $invite_user_mail_format = new InviteUserMailFormat($invited_user, $inviter_name);
        $new_user = $invite_user_mail_format->run();
        return  $new_user;
    }

}
