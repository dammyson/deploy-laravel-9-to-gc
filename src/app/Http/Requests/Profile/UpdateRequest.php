<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @todo add validations
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'gender' => 'sometimes|required|string',
            'dob' => 'sometimes|required|date',
            'baby' => 'sometimes|required|boolean',
            'relationship_status' => 'sometimes|required|string',
            'state' => 'sometimes|required|string',
            'avatar' => 'sometimes|sometimes|required|url',
        ];
    }
}
