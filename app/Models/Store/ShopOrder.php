<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $guarded = ['id'];

    /**
     * Many to Many relationship with ProductInstance
     * Added 'quantity' to the pivot table 'order_products' which can be accessed by $shopOrder->productInstances(1)->pivot->quantity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productInstances()
    {
        return $this->belongsToMany('App\Models\Store\ProductInstance', 'order_product')->withPivot('quantity');
    }

    public function liquidProducts()
    {
        return $this->hasMany('App\Models\Store\LiquidProduct');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Store\Customer');
    }

    public function discounts()
    {
        return $this->belongsToMany('App\Models\Store\Discount', 'order_discount');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Store\Payment');
    }
}
