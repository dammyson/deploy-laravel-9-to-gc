<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Services\BaseServiceInterface;
use App\Models\User;
use App\Services\Utilities\PostHttpRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class TransferService implements BaseServiceInterface
{
    protected $data, $user;

    public function __construct($data,$user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function run()
    {

        if($this->data['local']){
            $res = $this->SendLocal($this->data);
            if($res){
              return $res->data;
            }else{
             Log::error("::::::::::Card was not created");
             throw new Exception("Card was not created");
            }
        }else{
            $res = $this->SendToExternal($this->data);
            if($res){
              return $res->data;
            }else{
             Log::error("::::::::::Card was not created");
             throw new Exception("Card was not created");
            }
        }
       
       
    }



    private function SendToExternal($data)
    {
      
        $url = env('BASE_URL') . 'accounts/transfer';

        $body=[
            "debitAccountId"=> $data['debit_account_id'],
            "beneficiaryAccountNumber"=> $data['account_number'],
            "beneficiaryBankCode"=> $data['bank_code'],
            "amount"=>  $data['amount'],
            "paymentReference"=> $this->RandomString(),
            "narration"=>$data['narration'],
        ];
            $res = new PostHttpRequest($url,$body);
            $res =  $res->run();
            return $res;
    }


    private function SendLocal($data)
    {
        $url = env('BASE_URL') . 'accounts/transfer';

        $body=[
            "debitAccountId"=> $data['debit_account_id'],
            "creditAccountId"=> $data['credit_account_id'],
            "amount"=>  $data['amount'],
            "paymentReference"=> $this->RandomString(),
            "narration"=>$data['narration'],
        ];


            $res = new PostHttpRequest($url,$body);
            $res =  $res->run();
            return $res;
    }
    
   
   
    function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

}
