<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class ProductInstance extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'price',
        'stock',
        'redline',
        'active',
        'store',
        'product_id'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Many to One
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo('App\Models\Store\Product');
    }

    /**
     * Many to Many
     * @return $this
     */
    public function orderProduct() {
        return $this->belongsToMany('App\Models\Store\ShopOrder', 'order_product')->withPivot('quantity', 'id');
    }
}
