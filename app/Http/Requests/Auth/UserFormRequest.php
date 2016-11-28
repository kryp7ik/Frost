<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class UserFormRequest extends Request
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
            'store' => 'required',
            'password' => 'min:6|confirmed',
            'password_confirmation' => 'min:6',
        ];
    }
}
