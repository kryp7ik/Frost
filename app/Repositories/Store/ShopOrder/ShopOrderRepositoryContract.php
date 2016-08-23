<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/10/16
 * Time: 2:35 PM
 */
namespace App\Repositories\Store\ShopOrder;

use App\Models\Store\ShopOrder;

interface ShopOrderRepositoryContract
{
    /**
     * Retrieves all orders and optionally accepts a date range.  If no end date is specified the current time will be used.
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($startDate = null, $endDate = null);

    /**
     * Retrieves a single order by it's id.
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Retrieves all incomplete orders for the designated store
     * @param int $store_id
     * @return mixed
     */
    public function getIncompleteForStore($store_id);

    /**
     * Retrieves all orders for the designated store and optionally accepts a date range.
     * @param int $store_id
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getByStore($store_id, $startDate = null, $endDate = null);

    /**
     * Creates a new order and all associated entities.
     * @param int $store_id
     * @param array $data
     * @return ShopOrder|bool
     */
    public function create($store_id, $data);

    /**
     * Attaches a single ProductInstance to an order with the quantity in the join table
     * @param ShopOrder $order
     * @param array $data
     * @return bool
     */
    public function addProductToOrder(ShopOrder $order, $data);

    /**
     * Detaches a Product Instance from the order
     * @param ShopOrder $order
     * @param int $product_id
     */
    public function removeProductFromOrder(ShopOrder $order, $product_id);

    /**
     * @param ShopOrder $order
     * @param array $data
     */
    public function addLiquidToOrder(ShopOrder $order, $data);

    /**
     * @param int $liquid_id The id of the LiquidProduct to be deleted
     */
    public function removeLiquidFromOrder($liquid_id);
}