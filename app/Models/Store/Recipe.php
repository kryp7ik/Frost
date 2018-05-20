<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

/**
 * Actions for this entity can be found in App\Http\Controllers\Admin\Store\RecipeController
 * Class Recipe
 * @package App\Models\Store
 */
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
        'category',
        'description',
        'active'
    ];

    /**
     * Many to Many
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
