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
     * @param int $product_id
     * @param int $store_id
     * @param array $data
     * @return ProductInstance|bool
     */
    public function create($product_id, $store_id, $data);

    /**
     * @param int $id
     * @param array $data
     * @return ProductInstance
     */
    public function update($id, $data);
}