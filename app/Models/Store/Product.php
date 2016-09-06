<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'category',
        'cost'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $categoriesArray = array(
        'accessory' => 'Accessory',
        'battery' => 'Battery',
        'tank' => 'Tank',
        'rda' => 'RDA',
        'regulated_mod' => 'Regulated Mod',
        'mechanical_mod' => 'Mechanical Mod',
        'liquid' => 'Liquid',
        'coil' => 'Coil',
        'drip_tip' => 'Drip Tip',
        'beverage' => 'Beverage',
        'tax_exempt' => 'Tax Exempt'
    );

    /**
     * One to Many
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productInstances() {
        return $this->hasMany('App\Models\Store\ProductInstance');
    }
}
