<?php

namespace App\Models\Store;

use App\Services\Store\ShopOrderCalculator;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'store',
        'customer_id',
        'user_id',
        'subtotal',
        'total',
        'complete'
    ];

    /**
     * Many to Many relationship with ProductInstance
     * Added 'quantity' to the pivot table 'order_products' which can be accessed by $shopOrder->productInstances(1)->pivot->quantity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productInstances()
    {
        return $this->belongsToMany('App\Models\Store\ProductInstance', 'order_product')->withPivot('quantity', 'id');
    }

    public function liquidProducts()
    {
        return $this->hasMany('App\Models\Store\LiquidProduct');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Store\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    public function discounts()
    {
        return $this->belongsToMany('App\Models\Store\Discount', 'order_discount')->withPivot('id', 'applied');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Store\Payment');
    }

    /**
     * @return ShopOrderCalculator
     */
    public function calculator() {
        return new ShopOrderCalculator($this);
    }
}
