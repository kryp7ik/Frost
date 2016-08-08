<?php

namespace App\Http\Controllers\Front\Store;

use App\Models\Store\LiquidProduct;
use App\Models\Store\Recipe;
use App\Models\Store\ShopOrder;
use App\Http\Requests\Store\ShopOrderFormRequest;
use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
{
    protected $productInstances;

    protected $recipes;

    protected $liquidProducts;

    public function __construct(
        ProductInstanceRepositoryContract $instances,
        RecipeRepositoryContract $recipes,
        LiquidProductRepositoryContract $liquids)
    {
        $this->productInstances = $instances;
        $this->recipes = $recipes;
        $this->liquidProducts = $liquids;
    }

    public function index(Request $request)
    {
        $store = Auth::user()->store;
        if ($request->get('start') != null){
            // Change date range if user submits the date filter form
            $start = new DateTime($request->get('start'));
            $end = ($request->get('end') != null ) ? new DateTime($request->get('end')) : new DateTime('now');
            $orders = ShopOrder::
                where('store',$store)
                ->whereBetween('created_at', [$start, $end])->get();
            return view('orders.index', compact('orders'));
        } else {
            // If no filter is applied select orders from the last day
            $date = new \DateTime('now');
            $date->modify('-1 day');
            $orders = ShopOrder::where([
                ['store', '=', $store],
                ['created_at', '>', $date]
            ])->get();
            return view('orders.index', compact('orders'));
        }
    }

    public function create()
    {
        $recipes = $this->recipes->getAll(true);
        $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);
        return view('orders.create', compact('recipes', 'sortedInstances'));
    }

    public function store(ShopOrderFormRequest $request)
    {
        $order = new ShopOrder([
            'store' => Auth::user()->store,
        ]);
        $order->save();
        foreach ($request->get('products') as $productInstance) {
            $order->productInstances()->attach([$productInstance['instance'] => ['quantity' => $productInstance['quantity']]]);
        }
        $this->liquidProducts->createMultiple($order->id, $request->get('liquids'));
        return redirect('/orders/' . $order->id . '/show');
    }

    public function show($id)
    {
        $order = ShopOrder::whereId($id)->first();
        if (!$order instanceof ShopOrder) return redirect('/orders')->with('warning', 'Order with id: ' . $id . ' could not be found');
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
