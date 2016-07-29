<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Product;
use App\Models\Store\ProductInstance;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ProductFormRequest;
use App\Http\Requests\Store\ProductInstanceFormRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('backend.store.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::whereId($id)->firstOrFail();
        return view('backend.store.products.show', compact('product'));
    }

    public function create()
    {
        $product = new Product();
        return view('backend.store.products.create', compact('product'));
    }

    public function store(ProductFormRequest $request)
    {
        $cost = (is_float($request->get('cost'))) ? number_format($request->get('cost'), 2) : '0.00';
        $product = new Product([
            'name' => $request->get('name'),
            'cost' => $request->get('cost'),
            'sku' => $request->get('sku'),
            'category' => $request->get('category')
        ]);
        $product->save();
        return redirect('/admin/store/products')->with('status', 'Product successfully created');
    }

    public function edit($id)
    {
        $product = Product::whereId($id)->firstOrFail();
        return view('backend.store.products.edit', compact('product'));
    }

    public function update($id, ProductFormRequest $request)
    {
        $product = Product::whereId($id)->firstOrFail();
        $product->name = $request->get('name');
        $product->cost = $request->get('cost');
        $product->sku = $request->get('sku');
        $product->category = $request->get('category');
        $product->save();
        return redirect('/admin/store/products/' . $product->id . '/show')->with('status', 'Product edited successfully');
    }

    /**
     * @param $id The id of the parent Product
     * @param ProductInstanceFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addInstance($id, ProductInstanceFormRequest $request)
    {
        $instance = new ProductInstance(array(
            'price' => number_format($request->get('price'), 2),
            'stock' => $request->get('stock'),
            'redline' => $request->get('redline'),
            'store' => Auth::user()->store,
            'active' => true,
            'product_id' => $id,
        ));
        $instance->save();
        return back()->with('status', 'Product Instance has been created successfully');
    }

    public function editInstance($id)
    {
        $instance = ProductInstance::whereId($id)->firstOrFail();
        return view('backend.store.products.editinstance', compact('instance'));
    }

    public function updateInstance($id, ProductInstanceFormRequest $request)
    {
        $instance = ProductInstance::whereId($id)->firstOrFail();
        $instance->price = number_format($request->get('price'), 2);
        $instance->stock = $request->get('stock');
        $instance->redline = $request->get('redline');
        $instance->active = ($request->get('active')) ? true : false;
        $instance->save();
        $pid = $instance->product_id;
        return redirect('/admin/store/products/' . $pid . '/show')->with('status', 'Product Instance has been updated successfully');
    }
}
