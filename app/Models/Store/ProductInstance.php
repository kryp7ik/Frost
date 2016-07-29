<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class ProductInstance extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function product() {
        $this->belongsTo('App\Models\Store\Product');
    }

    public function orderProduct() {
        $this->belongsToMany('App\Models\Store\ShopOrder', 'order_product')->withPivot('quantity');
    }
}
