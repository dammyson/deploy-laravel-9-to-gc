<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\Request;

class TransferRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'debit_account_id' => 'required|string',
            'amount' => 'required|numeric',
            'narration'=> 'required|string',
            'local'=> 'required|boolean',
            'credit_account_id' => 'required_if:local,==,true|nullable|string',
            'account_number' => 'required_if:local,==,false|nullable|string',
            'bank_code' => 'required_if:local,==,false|nullable|string',
        ];
    }
}