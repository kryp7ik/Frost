<?php

namespace App\Http\Controllers\Front\Store;

use App\Models\Store\ProductInstance;
use App\Models\Store\Recipe;
use App\Models\Store\ShopOrder;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        if ($request->get('start') != null){
            // Change date range if user submits the date filter form
            $start = new DateTime($request->get('start'));
            $end = (strlen($request->get('end')) > 1) ? new DateTime($request->get('end')) : new DateTime('now');
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
        $recipes = Recipe::where('active', 1)->get();
        $productInstances = ProductInstance::where('store', Auth::user()->store)->where('active', 1)->get();

        return view('orders.create', compact('recipes', 'productInstances'));
    }



    public function show($id)
    {
        $order = ShopOrder::whereId($id)->first();
        if (!$order instanceof ShopOrder) return redirect('/orders')->with('warning', 'Order with id: ' . $id . ' could not be found');
        return view('orders.show', compact('order'));
    }

    public function checkout($id)
    {
        $order = ShopOrder::whereId($id)->firstOrFail();

        return view('orders.checkout', compact('order'));
    }
}
