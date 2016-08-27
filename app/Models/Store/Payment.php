<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'shop_order_id',
        'type',
        'amount'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Store\ShopOrder');
    }
}
