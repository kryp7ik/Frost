<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 8/10/16
 * Time: 2:35 PM
 */
namespace App\Repositories\Store\ShopOrder;

use App\Models\Auth\User;
use App\Models\Store\Discount;
use App\Models\Store\ShopOrder;
use App\Models\Store\Customer;
use App\Repositories\Store\Discount\DiscountRepositoryContract;

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
     * @param bool $eager If true load the entire object with all associated entities
     * @return mixed
     */
    public function findById($id, $eager = false);

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
     * Attaches one or more ProductInstance to an order with the quantity in the join table
     * @param ShopOrder $order
     * @param array $data
     * @return bool
     */
    public function addProductsToOrder(ShopOrder $order, $data);

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
     * Adds one or more LiquidProducts to an order
     * @param ShopOrder $order
     * @param array $data
     */
    public function addLiquidsToOrder(ShopOrder $order, $data);

    /**
     * Removes one LiquidProduct from the order
     * @param ShopOrder $order the order being modified
     * @param int $liquid_id The id of the LiquidProduct to be deleted
     */
    public function removeLiquidFromOrder(ShopOrder $order, $liquid_id);

    /**
     * @param ShopOrder $order
     * @param Discount $discount
     */
    public function addDiscountToOrder(ShopOrder $order, Discount $discount);

    /**
     * If the discount is being added by redeeming reward points update the customers points
     * @param ShopOrder $order
     * @param Discount $discount
     */
    public function addRedeemedDiscount(ShopOrder $order, Discount $discount);

    /**
     * @param ShopOrder $order
     * @param int $discount_pivot_id
     */
    public function removeDiscountFromOrder(ShopOrder $order, $discount_pivot_id);

    /**
     * Adds or updates the customer for the given order
     * If customer is being added after the order is complete update customers points
     * @param ShopOrder $order
     * @param Customer $customer
     * @param DiscountRepositoryContract
     */
    public function addCustomerToOrder(ShopOrder $order, Customer $customer, DiscountRepositoryContract $discountRepo);

    /**
     * @param ShopOrder $order
     * @param array $data
     * @return float $change Amount of change due (eg. Order->total = 10, Customer pays with $20, $change = 10)
     */
    public function addPaymentToOrder(ShopOrder $order, $data);

    /**
     * Deletes a payment and sets the order to incomplete so the user can go back to the open-order view to fix the error
     * @param int $payment_id
     */
    public function deletePayment($payment_id);
}