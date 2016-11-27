<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 10:58 AM
 */
namespace App\Repositories\Store\Ingredient;

interface IngredientRepositoryContract
{
    public function getAll();

    public function findById($id);

    public function create($data);

    public function update($id, $data);
}