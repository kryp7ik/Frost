<?php

namespace App\Http\Controllers\Front\Store;

use App\Models\Store\ShopOrder;
use App\Http\Requests\Store\ShopOrderFormRequest;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id)
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
            return view('orders.show.open', compact('order', 'recipes', 'sortedInstances'));
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProduct($id, Request $request)
    {
        $order = $this->orders->findById($id, true);
        $this->orders->addProductToOrder($order, $request->all());
        return back();
    }

    /**
     * @param int $id
     * @param int $pid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeProduct($id, $pid)
    {
        $order = $this->orders->findById($id, true);
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
        $order = $this->orders->findById($id, true);
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
        $order = $this->orders->findById($id, true);
        $this->orders->removeLiquidFromOrder($order, $lid);
        return back();
    }

}
