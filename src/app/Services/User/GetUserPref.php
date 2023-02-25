<?php

namespace App\Services\User;

use App\Models\EventKinds;
use App\Models\User;
use App\Services\BaseServiceInterface;

class GetUserPref implements BaseServiceInterface
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {
        $user_pref = [];
     
       


      
        $events =  EventKinds::whereIn('id', json_decode($pref->prefs->prefs))->get();
        foreach($events as $value){
            array_push($user_pref,$value->name);
        }
        return $user_pref ;
    }
}
