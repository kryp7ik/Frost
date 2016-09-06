<?php

namespace App\Repositories\Store\ShopOrder;

use App\Models\Auth\User;
use App\Models\Store\Customer;
use App\Models\Store\Discount;
use App\Models\Store\Payment;
use App\Models\Store\ShopOrder;
use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;
use Illuminate\Support\Facades\DB;

class EloquentShopOrderRepository implements ShopOrderRepositoryContract
{
    protected $liquidProductsRepository;

    public function __construct(LiquidProductRepositoryContract $liquidRepo)
    {
        $this->liquidProductsRepository = $liquidRepo;
    }

    /**
     * Retrieves all orders and optionally accepts a date range.  If no end date is specified the current time will be used.
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($startDate = null, $endDate = null)
    {
        if ($startDate) {
            if (!$endDate) $endDate = $startDate . ' 23:59:59';
            $startDate .= ' 00:00:00';
            return ShopOrder::whereBetween('created_at', [$startDate, $endDate])->get();
        }
        return ShopOrder::all();
    }

    /**
     * Retrieves a single order by it's id.
     * @param int $id
     * @param bool $eager If true load the entire object with all associated entities
     * @return mixed
     */
    public function findById($id, $eager = false)
    {
        if ($eager) {
            return ShopOrder::with('liquidProducts', 'productInstances', 'discounts', 'payments')->where('id', $id)->first();
        } else {
            return ShopOrder::where('id', $id)->first();
        }
    }

    /**
     * Retrieves all incomplete orders for the designated store
     * @param int $store_id
     * @return mixed
     */
    public function getIncompleteForStore($store_id)
    {
        return ShopOrder::
            where('complete', 0)
            ->where('store', $store_id)->get();
    }

    /**
     * Retrieves all orders for the designated store and optionally accepts a date range.
     * @param int $store_id
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getByStore($store_id, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            if (!$endDate) $endDate = $startDate . ' 23:59:59';
            $startDate .= ' 00:00:00';
            return ShopOrder::
                where('store',$store_id)
                ->whereBetween('created_at', [$startDate, $endDate])->get();
        } else {
            return ShopOrder::where('store', $store_id)->get();
        }
    }

    /**
     * Creates a new order and all associated entities.
     * @param User $user
     * @param array $data
     * @return ShopOrder|bool
     */
    public function create(User $user, $data)
    {
        $order = new ShopOrder([
            'store' => $user->store,
            'user_id' => $user->id,
        ]);
        if($order->save()) {
            foreach ($data['liquids'] as $liquid){
                $this->liquidProductsRepository->create($order->id, $user->store, $liquid);
            }
            foreach ($data['products'] as $product) {
                $this->addProductToOrder($order, $product);
            }
            $order->calculator()->calculateTotal();
            flash('The order has been created successfully', 'success');
            return $order;
        }
        flash('Something went wrong while trying to create a new order', 'danger');
        return false;
    }

    /**
     * Deletes an order and all associated entities
     * @param int $order_id
     * @return bool
     */
    public function delete($order_id)
    {
        $order = $this->findById($order_id);
        if($order->complete) {
            flash('You cannot delete an order that has been paid for', 'danger');
            return false;
        } else {
            foreach ($order->liquidProducts as $liquid) {
                $liquid->delete();
            }
            $order->delete();
            flash('The order has been deleted successfully', 'success');
            return true;
        }
    }

    /**
     * Attaches a single ProductInstance to an order with the quantity in the join table
     * @param ShopOrder $order
     * @param array $data
     * @return boolean
     */
    public function addProductToOrder(ShopOrder $order, $data)
    {
        if ($data['quantity'] == 0) return false;
        $order->productInstances()->attach([$data['instance'] => ['quantity' => $data['quantity']]]);
        $order->save();
        $order->calculator()->calculateTotal();
        flash('A product has been successfully added to the order', 'success');
        return true;
    }

    /**
     * Updates the quantity of the given product by +1 or -1.
     * @param int $order_id
     * @param int $product_pivot_id
     * @param string $increment
     */
    public function updateProductQuantity($order_id, $product_pivot_id, $increment)
    {
        if ($increment == 'plus') {
            DB::table('order_product')->where('id', $product_pivot_id)->increment('quantity');
        } else {
            DB::table('order_product')->where('id', $product_pivot_id)->decrement('quantity');
        }
        $order = $this->findById($order_id);
        $order->calculator()->calculateTotal();
        flash('Quantity has been successfully updated', 'success');
    }

    /**
     * Detaches a Product Instance from the order using the 'id' in the pivot table in case of duplicate products on one order
     * @param ShopOrder $order
     * @param int $product_pivot_id
     */
    public function removeProductFromOrder(ShopOrder $order, $product_pivot_id)
    {
        DB::table('order_product')->where('id', $product_pivot_id)->delete();
        $order->calculator()->calculateTotal();
        flash('A product has been successfully removed from the order', 'info');
    }

    /**
     * @param ShopOrder $order
     * @param array $data
     */
    public function addLiquidToOrder(ShopOrder $order, $data)
    {
        $this->liquidProductsRepository->create($order->id, $order->store, $data);
        $order->calculator()->calculateTotal();
        flash('A liquid has been successfully added to the order', 'success');
    }

    /**
     * @param ShopOrder $order the order being modified
     * @param int $liquid_id The id of the LiquidProduct to be deleted
     */
    public function removeLiquidFromOrder(ShopOrder $order, $liquid_id)
    {
        $this->liquidProductsRepository->delete($liquid_id);
        $order->calculator()->calculateTotal();
        flash('A liquid has been successfully removed from the order', 'info');
    }

    /**
     * @param ShopOrder $order
     * @param Discount $discount
     */
    public function addDiscountToOrder(ShopOrder $order, Discount $discount)
    {
        if ($discount->type == 'amount') {
            $order->discounts()->attach($discount->id, ['applied' => $discount->amount]);
        } else {
            $order->discounts()->attach($discount->id);
        }
        $order->calculator()->calculateTotal();
        flash('A discount has been successfully added to the order', 'success');
    }

    /**
     * If the discount is being added by redeeming reward points update the customers points
     * @param ShopOrder $order
     * @param Discount $discount
     */
    public function addRedeemedDiscount(ShopOrder $order, Discount $discount)
    {
        $customer = $order->customer;
        if ($customer->points >= $discount->value) {
            $this->addDiscountToOrder($order, $discount);
            $customer->points -= $discount->value;
            $customer->save();
        } else {
            flash('Customer does not have enough points for that discount!', 'danger');
        }
    }

    /**
     * @param ShopOrder $order
     * @param int $discount_pivot_id
     */
    public function removeDiscountFromOrder(ShopOrder $order, $discount_pivot_id)
    {
        DB::table('order_discount')->where('id', '=', $discount_pivot_id)->delete();
        $order->calculator()->calculateTotal();
        flash('A discount has been successfully removed from the order', 'info');
    }

    /**
     * Adds or updates the customer for the given order
     * @param ShopOrder $order
     * @param Customer $customer
     */
    public function addCustomerToOrder(ShopOrder $order, Customer $customer)
    {
        $order->customer_id = $customer->id;
        $order->save();
        flash('A customer has been successfully added to the order', 'success');
    }

    /**
     * Adds a payment to the order
     * If the payment is cash it expects to have an amount
     * If the payment is credit it sets the amount applied to the remaining balance of the order
     * @param ShopOrder $order
     * @param array $data
     * @return float $change The amount of change due
     */
    public function addPaymentToOrder(ShopOrder $order, $data)
    {
        $change = 0;
        $balance = $order->calculator()->getRemainingBalance();
        if (isset($data['amount'])) {
            if ($data['amount'] > $balance) {
                $amount = $balance;
                $change = $data['amount'] - $balance;
            } else {
                $amount = $data['amount'];
            }
        } else {
            $amount = $balance;
        }
        $payment = new Payment([
            'shop_order_id' => $order->id,
            'type' => $data['type'],
            'amount' => $amount
        ]);
        $payment->save();
        return $change;
    }
}