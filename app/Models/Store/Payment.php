<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo('App\Models\Store\ShopOrder');
    }
}
