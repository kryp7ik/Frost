<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class LiquidProduct extends Model
{
    protected $guarded = ['id'];

    public $prices = array(
        '10' => '4.99',
        '30' => '11.99',
        '50' => '18.99',
        '100' => '34.99',
        '250' => '74.99',
    );

    public function recipe() {
        return $this->belongsTo('App\Models\Store\Recipe');
    }

    public function shopOrder() {
        return $this->belongsTo('App\Models\Store\ShopOrder');
    }

    public function getPrice() {
        return $this->prices[$this->size];
    }
}
