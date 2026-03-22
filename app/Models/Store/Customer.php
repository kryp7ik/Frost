<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Actions for this entity can be found in App\Http\Controllers\Front\Store\CustomerController
 * Class Customer
 * @package App\Models\Store
 */
class Customer extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\CustomerFactory::new();
    }

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
        'points',
        'preferred'
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
