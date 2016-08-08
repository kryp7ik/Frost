<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Product;
use App\Repositories\Store\Product\ProductRepositoryContract;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ProductFormRequest;
use App\Http\Requests\Store\ProductInstanceFormRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $products;

    protected $productInstances;

    public function __construct(ProductRepositoryContract $products, ProductInstanceRepositoryContract $instances)
    {
        $this->products = $products;
        $this->productInstances = $instances;
    }

    public function index()
    {
        $products = $this->products->getAll();
        return view('backend.store.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = $this->products->findById($id);
        return view('backend.store.products.show', compact('product'));
    }

    public function create()
    {
        $product = new Product();
        return view('backend.store.products.create', compact('product'));
    }

    public function store(ProductFormRequest $request)
    {
        if ($product = $this->products->create($request->all())) {
            return redirect('/admin/store/products/' . $product->id . '/show');
        } else return redirect('/admin/store/products');
    }

    public function edit($id)
    {
        $product = $this->products->findById($id);
        return view('backend.store.products.edit', compact('product'));
    }

    public function update($id, ProductFormRequest $request)
    {
        $product = $this->products->update($id, $request->all());
        return redirect('/admin/store/products/' . $product->id . '/show');
    }

    /**
     * POST action for editable fields AJAX update
     * @param Int $id
     * @param Request $request
     * @return Response::json
     */
    public function ajaxUpdate($id, Request $request)
    {
        if($this->products->updateField($id, $request->get('name'), $request->get('value'))) {
            return \Response::json(array('status' => 1));
        } else {
            return \Response::json(array('status' => 0));
        }
    }

    /**
     * @param int $id The id of the parent Product
     * @param ProductInstanceFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addInstance($id, ProductInstanceFormRequest $request)
    {
        $this->productInstances->create($id, Auth::user()->store, $request->all());
        return back();
    }

    public function editInstance($id)
    {
        $instance = $this->productInstances->findById($id);
        return view('backend.store.products.editinstance', compact('instance'));
    }

    public function updateInstance($id, ProductInstanceFormRequest $request)
    {
        $instance = $this->productInstances->update($id, $request->all());
        return redirect('/admin/store/products/' . $instance->product_id . '/show');
    }
}
