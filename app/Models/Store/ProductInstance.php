<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class ProductInstance extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function product() {
        return $this->belongsTo('App\Models\Store\Product');
    }

    public function orderProduct() {
        return $this->belongsToMany('App\Models\Store\ShopOrder', 'order_product')->withPivot('quantity');
    }
}
