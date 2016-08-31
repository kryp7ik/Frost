<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class ProductInstance extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'price',
        'stock',
        'redline',
        'active',
        'store',
        'product_id'
    ];

    public $timestamps = false;

    public function product() {
        return $this->belongsTo('App\Models\Store\Product');
    }

    public function orderProduct() {
        return $this->belongsToMany('App\Models\Store\ShopOrder', 'order_product')->withPivot('quantity', 'id');
    }
}
