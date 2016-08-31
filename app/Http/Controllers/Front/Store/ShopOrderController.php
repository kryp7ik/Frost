<?php

namespace App\Http\Controllers\Front\Store;

use App\Http\Requests\Store\ShopOrderFormRequest;
use App\Repositories\Store\Customer\CustomerRepositoryContract;
use App\Repositories\Store\Discount\DiscountRepositoryContract;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;
use App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
{
    /**
     * @var ProductInstanceRepositoryContract
     */
    protected $productInstances;

    /**
     * @var RecipeRepositoryContract
     */
    protected $recipes;

    /**
     * @var ShopOrderRepositoryContract
     */
    protected $orders;

    /**
     * ShopOrderController constructor.
     * @param ProductInstanceRepositoryContract $instances
     * @param RecipeRepositoryContract $recipes
     * @param ShopOrderRepositoryContract $orders
     */
    public function __construct(
        ProductInstanceRepositoryContract $instances,
        RecipeRepositoryContract $recipes,
        ShopOrderRepositoryContract $orders)
    {
        $this->productInstances = $instances;
        $this->recipes = $recipes;
        $this->orders = $orders;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $date = ($request->get('start')) ? $request->get('start') : date('Y-m-d');
        $orders = $this->orders->getByStore(Auth::user()->store, $date);
        return view('orders.index', compact('orders', 'date'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $recipes = $this->recipes->getAll(true);
        $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);
        return view('orders.create', compact('recipes', 'sortedInstances'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $order = $this->orders->create(Auth::user(), $request->all());
        return ($order) ? redirect('/orders/' . $order->id . '/show') : redirect('/orders/create');
    }

    /**
     * @param int $id
     * @param DiscountRepositoryContract $discountRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id, DiscountRepositoryContract $discountRepo)
    {
        $order = $this->orders->findById($id, true);
        if (!$order) {
            flash('Order with id: ' . $id . ' could not be found', 'danger');
            return redirect('/orders');
        }
        if ($order->complete) {
            // If the order is complete display the order details only
            return view('orders.show.closed', compact('order'));
        } else {
            // If the order is open display a view that allows the user to modify the order and cash out
            $recipes = $this->recipes->getAll(true);
            $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);
            $sortedDiscounts = $discountRepo->getAll(true);
            $orderDiscount = $order->calculator()->getDiscountAppliedTotals();
            return view('orders.show.open', compact('order', 'recipes', 'sortedInstances', 'sortedDiscounts', 'orderDiscount'));
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $this->orders->delete($id);
        return redirect('/orders/create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProduct($id, Request $request)
    {
        $order = $this->orders->findById($id);
        $this->orders->addProductToOrder($order, $request->all());
        return back();
    }

    public function quantityUpdate($id, Request $request)
    {
        if($request->query('pid') && $request->query('inc')) {
            $this->orders->updateProductQuantity($id, $request->query('pid'), $request->query('inc'));
        }
        return back();
    }

    /**
     * @param int $id
     * @param int $pid the id of the row in the pivot table
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeProduct($id, $pid)
    {
        $order = $this->orders->findById($id);
        $this->orders->removeProductFromOrder($order, $pid);
        return back();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLiquid($id, Request $request)
    {
        $order = $this->orders->findById($id);
        $this->orders->addLiquidToOrder($order, $request->all());
        return back();
    }

    /**
     * @param int $id
     * @param int $lid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeLiquid($id, $lid)
    {
        $order = $this->orders->findById($id);
        $this->orders->removeLiquidFromOrder($order, $lid);
        return back();
    }

    /**
     * @param int $id
     * @param Request $request
     * @param DiscountRepositoryContract $discountRepo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDiscount($id, Request $request, DiscountRepositoryContract $discountRepo)
    {
        $order = $this->orders->findById($id);
        $discount = $discountRepo->findById($request->get('discount'));
        if ($discount->approval) {
            if ($request->get('pin') == config('store.manager_pin')) {
                $this->orders->addDiscountToOrder($order, $discount->id);
            } else {
                flash('Sorry the manager pin was incorrect', 'danger');
            }
            return back();
        } else {
            $this->orders->addDiscountToOrder($order, $discount->id);
            return back();
        }
    }

    /**
     * @param int $id
     * @param int $did
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeDiscount($id, $did)
    {
        $order = $this->orders->findById($id);
        $this->orders->removeDiscountFromOrder($order, $did);
        return back();
    }

    /**
     * Adds or changes the customer for the given order
     * @param int $id ID of the ShopOrder that is being modified
     * @param Request $request
     * @param CustomerRepositoryContract $customerRepo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function customer($id, Request $request, CustomerRepositoryContract $customerRepo)
    {
        $order = $this->orders->findById($id);
        $customer = $customerRepo->findByPhone($request->get('phone'));
        if (!$customer) return back();
        $this->orders->addCustomerToOrder($order, $customer);
        return back();
    }

    public function payment($id, Request $request)
    {
        $order = $this->orders->findById($id);
    }
}
