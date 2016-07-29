<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $guarded = ['id'];

    public function recipes() {
        return $this->belongsToMany('App\Models\Store\Recipe', 'recipe_ingredient')->withPivot('amount');
    }
}
