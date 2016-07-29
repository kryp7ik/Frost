<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

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

    public function productInstances() {
        return $this->hasMany('App\Models\Store\ProductInstance');
    }
}
