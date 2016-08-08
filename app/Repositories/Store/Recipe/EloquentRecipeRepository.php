<?php

namespace App\Repositories\Store\Recipe;

use App\Models\Store\Recipe;

class EloquentRecipeRepository implements RecipeRepositoryContract
{

    /**
     * @param bool $active If true returns all recipes that are active else returns all
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($active = false)
    {
        if ($active) {
            return Recipe::where('active', 1)->get();
        } else {
            return Recipe::all();
        }
    }

    /**
     * @param int $id
     * @return Recipe|boolean
     */
    public function findById($id)
    {
        return Recipe::where('id', $id)->firstOrFail();
    }

    /**
     * @param array $data Form data ['name', $ingredients[]['ingredient', 'amount']
     * @return Recipe
     */
    public function create($data)
    {
        $recipe = new Recipe(array(
            'name' => $data['name'],
            'active' => true,
        ));
        $recipe->save();
        foreach ($data['ingredients'] as $ingredientFieldset) {
            $this->addIngredient($recipe, $ingredientFieldset);
        }
        flash('The recipe has been created successfully', 'success');
        return $recipe;
    }

    /**
     * @param Recipe $recipe
     * @param array $data The Ingredient Fieldset ['ingredient' => $ingredient_id, 'amount' => (int) $amountInMl]
     */
    public function addIngredient(Recipe $recipe, $data) {
        $recipe->ingredients()->attach([$data['ingredient'] => ['amount' => $data['amount']]]);
    }

    /**
     * @param int $recipe_id
     * @param int $ingredient_id
     * @return Recipe
     */
    public function removeIngredient($recipe_id, $ingredient_id)
    {
        $recipe = Recipe::where('id', $recipe_id)->firstOrFail();
        $recipe->ingredients()->detach($ingredient_id);
        $recipe->save();
        flash('The ingredient has been successfully removed', 'success');
        return $recipe;
    }

    /**
     * @param int $id ID of the Recipe
     * @param string $fieldName Recipe attribute to be updated
     * @param mixed $value New value for the attribute
     * @return Recipe
     */
    public function updateField($id, $fieldName, $value)
    {
        if($recipe = Recipe::where('id', $id)->update([$fieldName => $value])) {
            flash('The Recipe has been updated successfully', 'success');
            return $recipe;
        }
        flash('Something went wrong while trying to update the Recipe', 'danger');
        return $recipe;
    }

}