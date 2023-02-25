<?php

namespace App\Services\User;

use App\Services\BaseServiceInterface;
use App\Models\Client;

class DeleteService implements BaseServiceInterface
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function run()
    {
        return $this->user->delete();
    }
}