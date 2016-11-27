<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\Request;

class ProductInstanceFormRequest extends Request
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
            'product' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'redline' => 'required|integer',

        ];
    }
}
