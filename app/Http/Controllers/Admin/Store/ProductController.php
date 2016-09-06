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
    /**
     * @var ProductRepositoryContract
     */
    protected $products;

    /**
     * @var ProductInstanceRepositoryContract
     */
    protected $productInstances;

    /**
     * ProductController constructor.
     * @param ProductRepositoryContract $products
     * @param ProductInstanceRepositoryContract $instances
     */
    public function __construct(ProductRepositoryContract $products, ProductInstanceRepositoryContract $instances)
    {
        $this->products = $products;
        $this->productInstances = $instances;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = $this->products->getAll();
        return view('backend.store.products.index', compact('products'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $product = $this->products->findById($id);
        return view('backend.store.products.show', compact('product'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $product = new Product();
        return view('backend.store.products.create', compact('product'));
    }

    /**
     * @param ProductFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ProductFormRequest $request)
    {
        if ($product = $this->products->create($request->all())) {
            return redirect('/admin/store/products/' . $product->id . '/show');
        } else return redirect('/admin/store/products');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $product = $this->products->findById($id);
        return view('backend.store.products.edit', compact('product'));
    }

    /**
     * @param int $id
     * @param ProductFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, ProductFormRequest $request)
    {
        $product = $this->products->update($id, $request->all());
        return redirect('/admin/store/products/' . $product->id . '/show');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxUpdate($id, Request $request)
    {
        if($this->products->updateField($id, $request->get('name'), $request->get('value'))) {
            return response()->json(array('status' => 1));
        } else {
            return response()->json(array('status' => 0));
        }
    }

    /**
     * @param ProductInstanceFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addInstance(ProductInstanceFormRequest $request)
    {
        $this->productInstances->create(Auth::user()->store, $request->all());
        return back();
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInstance($id)
    {
        $instance = $this->productInstances->findById($id);
        return view('backend.store.products.editinstance', compact('instance'));
    }

    /**
     * @param int $id
     * @param ProductInstanceFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateInstance($id, ProductInstanceFormRequest $request)
    {
        $instance = $this->productInstances->update($id, $request->all());
        return redirect('/admin/store/products/' . $instance->product_id . '/show');
    }
}
