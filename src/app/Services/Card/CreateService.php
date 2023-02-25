<?php

namespace App\Services\Card;

use App\Models\Account;
use App\Services\BaseServiceInterface;
use App\Models\User;
use App\Services\Utilities\PostHttpRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateService implements BaseServiceInterface
{
    protected $data, $user;

    public function __construct($data,$user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function run()
    {
       $res = $this->createApiCard($this->data, $this->user->customer_id);
       if($res){
         $new_account = $this->createLocalAccount($this->user, $res->data);
         return $res->data;
       }else{
        Log::error("::::::::::Card was not created");
        throw new Exception("Card was not created");
       }
       
    }



    private function createLocalAccount($data, $account)
    {
        dd($account);
        $user = new Account();
        $user->id = uniqid();
        $user->user_id = $data['user_id'];
        $user->account_number = $account->_id;
        $user->currency = $account->currency;
        $user->type = $account->accountType;
        $user->bank = $account->provider;
        $user->status = "active";
        $user->save();

       
        return $user;
    }


    private function createApiCard($data, $id)
    {
        $url = env('BASE_URL') . 'cards';

        $body=[
            "customerId"=>  $id,
            "type"=> $data['type'],
            "currency"=>  $data['currency'],
            "status"=>"active",
            "spendingControls"=>["channels"=>[
                "atm"=>true,"pos"=>true,"web"=>true,"mobile"=>true
            ],
            "sendPINSMS"=>false,
            ]
            ];


            $res = new PostHttpRequest($url,$body);
            $res =  $res->run();
            return $res;
    }
    
   
   

}
