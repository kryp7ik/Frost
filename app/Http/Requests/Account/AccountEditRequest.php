<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\Request;

class AccountEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'name' => 'required',
            'email' => 'required',
            'password' => 'min:8|confirmed',
            'password_confirmation' => 'min:8',
        ];
    }
}