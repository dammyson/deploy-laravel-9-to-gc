<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Services\BaseServiceInterface;
use App\Models\User;
use App\Services\Utilities\GetHttpRequest;
use App\Services\Utilities\PostHttpRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class ListService implements BaseServiceInterface
{
    protected $data, $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function run()
    {
        $list = [];
        $accounts = Account::where("user_id", $this->user->id)->get();
        foreach ($accounts as $account) {
            $res = $this->getApiAccount($account->merchant_card_id);
            if ($res) {
                array_push($list , $res->data );
            } else {
            }
        }

        return $list;
    }




    private function getApiAccount($id)
    {
        $url = env('BASE_URL') . 'accounts/' . $id. '/balance';
        $res = new GetHttpRequest($url);
        $res =  $res->run();
        return $res;
    }


    
}
