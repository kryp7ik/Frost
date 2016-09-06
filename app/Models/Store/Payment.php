<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'shop_order_id',
        'type',
        'amount'
    ];

    /**
     * Many to One
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Store\ShopOrder');
    }
}
