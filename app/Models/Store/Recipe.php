<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
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
        'active'
    ];

    /**
     * Many to Many
     * @return $this
     */
    public function ingredients() {
        return $this->belongsToMany('App\Models\Store\Ingredient', 'recipe_ingredient')->withPivot('amount');
    }

    /**
     * One to Many
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function liquidProducts() {
        return $this->hasMany('App\Models\Store\LiquidProduct');
    }
}
