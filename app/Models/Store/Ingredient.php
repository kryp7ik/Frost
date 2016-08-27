<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'vendor'
    ];

    public function recipes() {
        return $this->belongsToMany('App\Models\Store\Recipe', 'recipe_ingredient')->withPivot('amount');
    }
}
