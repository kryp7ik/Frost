<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = ['id'];

    public $typeArray = array(
        'amount' => 'Dollar Amount',
        'percent' => 'Percentage'
    );

    public $filterArray = array(
        'none' => 'No Filter',
        'product' => 'Products Only',
        'liquid' => 'Liquids Only',
    );

    public function orders()
    {
        return $this->belongsToMany('App\Models\Store\ShopOrder', 'order_discount');
    }

}
