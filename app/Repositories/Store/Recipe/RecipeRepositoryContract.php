<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 12:51 PM
 */
namespace App\Repositories\Store\Recipe;

use App\Models\Store\Recipe;

interface RecipeRepositoryContract
{
    /**
     * @param bool $active If true returns all recipes that are active else returns all
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($active = false);

    /**
     * @param int $id
     * @param bool $ingredients If true eager load the ingredients
     * @return Recipe|boolean
     */
    public function findById($id, $ingredients=false);

    /**
     * @param array $data Form data ['name', $ingredients[]['ingredient', 'amount']
     * @return Recipe
     */
    public function create($data);

    /**
     * @param Recipe $recipe
     * @param array $data The Ingredient Fieldset ['ingredient' => $ingredient_id, 'amount' => (int) $amountInMl]
     */
    public function addIngredient(Recipe $recipe, $data);

    /**
     * @param int $recipe_id
     * @param int $ingredient_id
     * @return Recipe
     */
    public function removeIngredient($recipe_id, $ingredient_id);

    /**
     * @param int $id ID of the Recipe
     * @param string $fieldName Recipe attribute to be updated
     * @param mixed $value New value for the attribute
     * @return Recipe
     */
    public function updateField($id, $fieldName, $value);
}