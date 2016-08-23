<?php

namespace App\Http\Controllers\Front\Store;

use App\Models\Store\Recipe;
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
    protected $productInstances;

    protected $recipes;

    protected $orders;

    public function __construct(
        ProductInstanceRepositoryContract $instances,
        RecipeRepositoryContract $recipes,
        ShopOrderRepositoryContract $orders)
    {
        $this->productInstances = $instances;
        $this->recipes = $recipes;
        $this->orders = $orders;
    }

    public function index(Request $request)
    {
        $store = Auth::user()->store;
        if ($request->get('start') != null){
            // Change date range if user submits the date filter form
            $orders = $this->orders->getByStore($store, $request->get('start'), $request->get('end'));
        } else {
            // If no filter is applied select orders from the last day
            $date = new \DateTime();
            $date->modify('-1 day');
            $orders = $this->orders->getByStore($store, $date->format('Y-m-d H:i:s'));
        }
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $recipes = $this->recipes->getAll(true);
        $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);
        return view('orders.create', compact('recipes', 'sortedInstances'));
    }

    public function store(Request $request)
    {
        $order = $this->orders->create(Auth::user()->store, $request->all());
        return ($order) ? redirect('/orders/' . $order->id . '/show') : redirect('/orders/create');
    }

    public function show($id)
    {
        $order = $this->orders->findById($id);
        if (!$order instanceof ShopOrder) {
            flash('Order with id: ' . $id . ' could not be found', 'danger');
            return redirect('/orders');
        }
        if ($order->complete) {
            // If the order is complete display the order details only
            return view('orders.show.closed', compact('order'));
        } else {
            // If the order is open display a view that allows the user to modify the order and cash out
            $recipes = Recipe::where('active', 1)->get();
            $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);
            return view('orders.show.open', compact('order', 'recipes', 'sortedInstances'));
        }
    }


}
