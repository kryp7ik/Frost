<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/7/16
 * Time: 11:40 AM
 */
namespace App\Repositories\Store\ProductInstance;

use App\Models\Store\ProductInstance;

interface ProductInstanceRepositoryContract
{
    /**
     * Retrieves all active Product Instances that belong the the designated store
     * @param int $store The id of the store belonging to the current user
     * @param bool $sorted If true returns a sorted array by category for usage in optgroups [$category][]['instance_id' => $id, 'name' => $instance->product->name]
     * @return array
     */
    public function getActiveWhereStore($store, $sorted = false);

    /**
     * @param int $id
     * @return ProductInstance|boolean
     */
    public function findById($id);

    /**
     * Returns all products where the stock is equal to or lower than redline
     * Optionally filter by a specific store default returns all.
     * @param int $store
     * @return mixed
     */
    public function getBelowRedline($store = 0);

    /**
     * @param int $store_id
     * @param array $data
     * @return ProductInstance|bool
     */
    public function create($store_id, $data);

    /**
     * @param int $id
     * @param array $data
     * @return ProductInstance
     */
    public function update($id, $data);

    /**
     * Adjust the stock quantity of a ProductInstance by a positive or negative value
     * @param ProductInstance $product
     * @param int $adjustment
     */
    public function updateStock(ProductInstance $product, $adjustment);
}