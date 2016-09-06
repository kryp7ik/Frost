<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
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
        'vendor'
    ];

    /**
     * Many to Many with pivot being the amount of the ingredient in the recipe
     * @return $this
     */
    public function recipes() {
        return $this->belongsToMany('App\Models\Store\Recipe', 'recipe_ingredient')->withPivot('amount');
    }
}
