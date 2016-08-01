<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = ['id'];

    public function orders()
    {
        return $this->hasMany('App\Models\Store\ShopOrder');
    }
}
