<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class LiquidProduct extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'shop_order_id',
        'recipe_id',
        'store',
        'size',
        'nicotine',
        'vg',
        'menthol',
        'extra',
        'mixed'
    ];

    public function recipe() {
        return $this->belongsTo('App\Models\Store\Recipe');
    }

    public function shopOrder() {
        return $this->belongsTo('App\Models\Store\ShopOrder');
    }

    /**
     * Returns the price of the LiquidProduct based on config settings and if Extra is true add $1
     * @return float $price
     */
    public function getPrice() {
        $price = ($this->extra) ? config('store.liquid_prices')[$this->size] + 1 : config('store.liquid_prices')[$this->size];
        return $price;
    }
}
