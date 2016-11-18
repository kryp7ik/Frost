<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 1:33 PM
 */
namespace App\Repositories\Store\LiquidProduct;

use App\Models\Store\LiquidProduct;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;

interface LiquidProductRepositoryContract
{
    public function getAll();

    /**
     * Returns all LiquidProducts that belong to the designated store and have not been mixed (completed)
     * @param int $store_id
     * @param bool $mutate If true sort results in a new array to use on touch screen
     * @return mixed
     */
    public function getIncompleteWhereStore($store_id, $mutate = false);

    /**
     * @param int $id
     * @return LiquidProduct|boolean
     */
    public function findById($id);

    /**
     * Returns the relevant information for the last LiquidProduct ordered for the given customer id or false if one was not found
     * @param int $customer_id
     * @param RecipeRepositoryContract $recipeRepo
     * @return array|bool
     */
    public function findCustomersLastLiquid($customer_id, RecipeRepositoryContract $recipeRepo);

    /**
     * Saves a new LiquidProduct to the database.
     * Returns the model on success or false on fail
     * @param int $shop_order_id
     * @param int $store_id
     * @param array $data
     * @return LiquidProduct|boolean
     */
    public function create($shop_order_id, $store_id, $data);

    /**
     * @param int $id
     * @param string $field_name
     * @param mixed $value
     * @return mixed
     */
    public function updateField($id, $field_name, $value);

    /**
     * @param int $id
     */
    public function delete($id);
}