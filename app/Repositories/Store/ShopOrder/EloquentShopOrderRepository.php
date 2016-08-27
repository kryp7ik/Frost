<?php

namespace App\Repositories\Store\ShopOrder;

use App\Models\Auth\User;
use App\Models\Store\ShopOrder;
use App\Models\Store\ShopOrderCalculator;
use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;

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
            if (!$endDate) $endDate = date('Y-m-d H:i:s');
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
            if (!$endDate) $endDate = date('Y-m-d H:i:s');
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
            //$calc = new ShopOrderCalculator($order);
            //$order = $calc->calculateTotal();
            flash('The order has been created successfully', 'success');
            return $order;
        }
        flash('Something went wrong while trying to create a new order', 'danger');
        return false;
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
        flash('A product has been successfully added to the order', 'success');
        return true;
    }

    /**
     * Detaches a Product Instance from the order
     * @param ShopOrder $order
     * @param int $product_id
     */
    public function removeProductFromOrder(ShopOrder $order, $product_id)
    {
        $order->productInstances()->detach($product_id);
        flash('A product has been successfully removed from the order', 'danger');
    }

    /**
     * @param ShopOrder $order
     * @param array $data
     */
    public function addLiquidToOrder(ShopOrder $order, $data)
    {
        $this->liquidProductsRepository->create($order->id, $order->store, $data);
        flash('A liquid has been successfully added to the order', 'success');
    }

    /**
     * @param int $liquid_id The id of the LiquidProduct to be deleted
     */
    public function removeLiquidFromOrder($liquid_id)
    {
        $this->liquidProductsRepository->delete($liquid_id);
        flash('A liquid has been successfully removed from the order', 'danger');
    }
}