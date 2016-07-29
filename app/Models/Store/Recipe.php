<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $guarded = ['id'];

    public function ingredients() {
        return $this->belongsToMany('App\Models\Store\Ingredient', 'recipe_ingredient')->withPivot('amount');
    }

    public function liquidProducts() {
        return $this->hasMany('App\Models\Store\LiquidProduct');
    }
}
