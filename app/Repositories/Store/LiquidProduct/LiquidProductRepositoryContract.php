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
     * @param int $limit The amount of results to limit t
     * @return array|bool
     */
    public function findCustomersLastLiquid($customer_id, RecipeRepositoryContract $recipeRepo, $limit);

    /**
     * Retrieves the last LiquidProduct(s) that were completed (mixed) for the designated store
     * Optionally return a formatted array for a json response
     * @param int $store_id
     * @param int $limit Number of results to limit
     * @param bool $mutate if true return formatted array for json response
     * @return array
     */
    public function getRecentlyCompletedWhereStore($store_id, $limit = 5, $mutate = true );

    /**
     * Set's the designated LiquidProducts 'mixed' attribute back to false
     * Returns true on success or false on fail
     * @param int $liquidProduct_id
     * @return bool
     */
    public function unMixLiquidProduct($liquidProduct_id);

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
     * Creates multiple LiquidProducts from an array of POST data
     * @param int $shop_order_id
     * @param int $store_id
     * @param array $data
     */
    public function createMultiple($shop_order_id, $store_id, $data);

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