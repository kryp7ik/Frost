<?php

namespace App\Http\Controllers\Admin\Store;

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
     * Returns a Product Management dashboard style view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return view('backend.store.products.home');
    }

    /**
     * Show all Products
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = $this->products->getAll();
        return view('backend.store.products.index', compact('products'));
    }

    /**
     * Display one Product with all of it's instances
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $product = $this->products->findById($id);
        return view('backend.store.products.show', compact('product'));
    }

    /**
     * Create a new product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backend.store.products.create');
    }

    /**
     * Save the product
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
     * Edit a Product
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $product = $this->products->findById($id);
        return view('backend.store.products.edit', compact('product'));
    }

    /**
     * Update a Product
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
     * Route used for "editable" fields which accepts a Product id and an attribute name ('name') and new value ('value')
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
     * Adds a ProdcutInstance to a Product
     * @param ProductInstanceFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addInstance(ProductInstanceFormRequest $request)
    {
        $this->productInstances->create(Auth::user()->store, $request->all());
        return back();
    }

    /**
     * Display the edit view for a ProductInstance
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInstance($id)
    {
        $instance = $this->productInstances->findById($id);
        return view('backend.store.products.editinstance', compact('instance'));
    }

    /**
     * Updates a ProductInstance
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateInstance($id, Request $request)
    {
        $instance = $this->productInstances->update($id, $request->all());
        return redirect('/admin/store/products/' . $instance->product_id . '/show');
    }

    /**
     * Displays an index of all ProductInstances that have a stock below the set redline
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redline()
    {
        $store = (Auth::user()->hasRole('manager')) ? 0 : Auth::user()->store;
        $productInstances = $this->productInstances->getBelowRedline($store);
        return view('backend.store.products.redline', compact('productInstances'));
    }
}
