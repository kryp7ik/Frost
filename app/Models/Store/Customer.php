<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'phone',
        'name',
        'email',
        'points'
    ];

    /**
     * One To Many
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Store\ShopOrder');
    }
}
