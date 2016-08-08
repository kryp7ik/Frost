<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = ['id'];

    /**
     * An array of available type options with the key being the database value and the value being the display value in the form
     * @var array
     */
    public $typeArray = array(
        'amount' => 'Dollar Amount',
        'percent' => 'Percentage'
    );

    /**
     * An array of available filter options with the [key] being the database value and the [value] being the display value in the form
     * @var array
     */
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
