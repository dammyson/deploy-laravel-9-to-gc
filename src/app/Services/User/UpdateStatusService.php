<?php

namespace App\Services\User;

use App\Models\User;

use App\Services\BaseServiceInterface;
use Illuminate\Support\Arr;

class UpdateStatusService implements BaseServiceInterface
{
    protected $validated;
    protected $user_id;
    

    public function __construct($validated, $user_id)
    {
        $this->validated = $validated;
        $this->user_id = $user_id;
    }

    public function run()
    {
        return $this->updateUser();
    }

    private function updateUser()
    { 
        $user = User::findOrFail($this->user_id);
        \DB::transaction(function () use ($user) {
          $user->status = $this->validated['status'];
          $user->save();
        });
        return $user;
    }

}
