<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/10/16
 * Time: 2:35 PM
 */
namespace App\Repositories\Store\ShopOrder;

use App\Models\Auth\User;
use App\Models\Store\ShopOrder;
use App\Models\Store\Customer;

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
     * @param User $user
     * @param array $data
     * @return ShopOrder|bool
     */
    public function create(User $user, $data);

    /**
     * Deletes an order and all associated entities
     * @param int $order_id
     * @return bool
     */
    public function delete($order_id);

    /**
     * Attaches a single ProductInstance to an order with the quantity in the join table
     * @param ShopOrder $order
     * @param array $data
     * @return bool
     */
    public function addProductToOrder(ShopOrder $order, $data);

    /**
     * Updates the quantity of the given product.
     * @param int $order_id
     * @param int $product_pivot_id
     * @param string $increment
     */
    public function updateProductQuantity($order_id, $product_pivot_id, $increment);

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
     * @param ShopOrder $order the order being modified
     * @param int $liquid_id The id of the LiquidProduct to be deleted
     */
    public function removeLiquidFromOrder(ShopOrder $order, $liquid_id);

    /**
     * @param ShopOrder $order
     * @param int $discount_id
     */
    public function addDiscountToOrder(ShopOrder $order, $discount_id);

    /**
     * @param ShopOrder $order
     * @param int $discount_pivot_id
     */
    public function removeDiscountFromOrder(ShopOrder $order, $discount_pivot_id);

    /**
     * Adds or updates the customer for the given order
     * @param ShopOrder $order
     * @param Customer $customer
     */
    public function addCustomerToOrder(ShopOrder $order, Customer $customer);
}