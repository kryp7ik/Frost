<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ProductFormRequest;
use App\Http\Requests\Store\ProductInstanceFormRequest;
use App\Repositories\Store\Product\ProductRepositoryContract;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryContract $products,
        protected ProductInstanceRepositoryContract $productInstances
    ) {
    }

    public function home(): InertiaResponse
    {
        return Inertia::render('Admin/Store/Products/Home');
    }

    public function index(): InertiaResponse
    {
        $products = $this->products->getAll();

        return Inertia::render('Admin/Store/Products/Index', [
            'products' => collect($products)->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'category' => $p->category,
                'cost' => $p->cost,
            ])->values(),
        ]);
    }

    public function show(int $id): InertiaResponse
    {
        $product = $this->products->findById($id);

        return Inertia::render('Admin/Store/Products/Show', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category,
                'cost' => $product->cost,
                'instances' => $product->productInstances->map(fn ($i) => [
                    'id' => $i->id,
                    'store' => $i->store,
                    'price' => $i->price,
                    'stock' => $i->stock,
                    'redline' => $i->redline,
                    'active' => (bool) $i->active,
                ])->values(),
            ],
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Admin/Store/Products/Create');
    }

    public function store(ProductFormRequest $request): RedirectResponse
    {
        if ($product = $this->products->create($request->all())) {
            return redirect('/admin/store/products/' . $product->id . '/show');
        }

        return redirect('/admin/store/products/index');
    }

    public function edit(int $id): InertiaResponse
    {
        $product = $this->products->findById($id);

        return Inertia::render('Admin/Store/Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category,
                'cost' => $product->cost,
            ],
        ]);
    }

    public function update(int $id, ProductFormRequest $request): RedirectResponse
    {
        $product = $this->products->update($id, $request->all());

        return redirect('/admin/store/products/' . $product->id . '/show');
    }

    public function ajaxUpdate(int $id, Request $request): JsonResponse
    {
        $status = $this->products->updateField($id, $request->get('name'), $request->get('value')) ? 1 : 0;

        return response()->json(['status' => $status]);
    }

    public function addInstance(ProductInstanceFormRequest $request): RedirectResponse
    {
        $this->productInstances->create(Auth::user()->store, $request->all());

        return back();
    }

    public function editInstance(int $id): InertiaResponse|RedirectResponse
    {
        $instance = $this->productInstances->findById($id);
        if (Auth::user()->store !== $instance->store && ! Auth::user()->hasRole('admin')) {
            flash('You can only edit product instances for your store!', 'danger');

            return back();
        }

        return Inertia::render('Admin/Store/Products/EditInstance', [
            'instance' => [
                'id' => $instance->id,
                'product_id' => $instance->product_id,
                'store' => $instance->store,
                'price' => $instance->price,
                'stock' => $instance->stock,
                'redline' => $instance->redline,
                'active' => (bool) $instance->active,
            ],
        ]);
    }

    public function updateInstance(int $id, Request $request): RedirectResponse
    {
        $instance = $this->productInstances->update($id, $request->all());

        return redirect('/admin/store/products/' . $instance->product_id . '/show');
    }

    public function redline(): InertiaResponse
    {
        $store = Auth::user()->hasRole('manager') ? 0 : Auth::user()->store;
        $productInstances = $this->productInstances->getBelowRedline($store);

        return Inertia::render('Admin/Store/Products/Redline', [
            'productInstances' => collect($productInstances)->map(fn ($i) => [
                'id' => $i->id,
                'product_name' => $i->product->name ?? '',
                'store' => $i->store,
                'price' => $i->price,
                'stock' => $i->stock,
                'redline' => $i->redline,
            ])->values(),
        ]);
    }
}
