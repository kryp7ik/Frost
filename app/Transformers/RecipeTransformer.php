<?php

namespace App\Transformers;

use App\Models\Store\Recipe;
use League\Fractal\TransformerAbstract;

class RecipeTransformer extends TransformerAbstract
{

    public function transform(Recipe $recipe)
    {
        return [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'active' => ($recipe->active) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>',
            'created_at' => date('m-d-Y', strtotime($recipe->created_at)),
            'view' => '<a href="/admin/store/recipes/' . $recipe->id . '/show" class="btn btn-xs btn-info" style="padding:8px;margin:0px;">View</a>'
        ];
    }
}