<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Services\BaseServiceInterface;
use App\Models\User;
use App\Services\Utilities\PostHttpRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateService implements BaseServiceInterface
{
    protected $data, $user;

    public function __construct($data,$user )
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function run()
    {
       $res = $this->createApiAccount($this->data, $this->user->customer_id);
       if($res){
         $new_account = $this->createLocalAccount($this->user, $res->data);
         return $new_account;
       }else{
        Log::error("::::::::::Card was not created");
        throw new Exception("Card was not created");
       }
       
    }



    private function createLocalAccount($data, $account)
    {
      
        $user = new Account();
        $user->id = uniqid();
        $user->user_id =  $data->id;
        $user->merchant_card_id = $account->_id;
        $user->account_number = $account->accountNumber;
        $user->account_name = $account->accountName;
        $user->bank_code = $account->bankCode;
        $user->currency = $account->currency;
        $user->type = $account->accountType;
        $user->bank = $account->provider;
        $user->status = "active";
        $user->save();

       
        return $user;
    }


    private function createApiAccount($data, $id)
    {
        $url = env('BASE_URL') . 'accounts';

        $body=[
            "customerId"=>  $id,
            "type"=> "wallet",
            "currency"=>  $data['currency'],
            "accountType"=>$data['account_type'],
            ];


            $res = new PostHttpRequest($url,$body);
            $res =  $res->run();
            return $res;
    }
    
   
   
   

}
