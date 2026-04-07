<?php

namespace App\Http\Controllers\Front\Store;

use App\Events\OrderCompleted;
use App\Http\Controllers\Controller;
use App\Jobs\SendReceiptEmail;
use App\Repositories\Store\Customer\CustomerRepositoryContract;
use App\Repositories\Store\Discount\DiscountRepositoryContract;
use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;
use App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ShopOrderController extends Controller
{
    public function __construct(
        protected ProductInstanceRepositoryContract $productInstances,
        protected RecipeRepositoryContract $recipes,
        protected ShopOrderRepositoryContract $orders
    ) {
    }

    public function index(Request $request): InertiaResponse
    {
        $date = $request->get('start') ?: date('Y-m-d');
        $orders = $this->orders->getByStore(Auth::user()->store, $date);

        return Inertia::render('Orders/Index', [
            'orders' => collect($orders)->map(fn ($o) => [
                'id' => $o->id,
                'customer_name' => $o->customer?->name,
                'total' => (float) $o->total,
                'complete' => (bool) $o->complete,
                'created_at' => $o->created_at?->toDateTimeString(),
            ])->values(),
            'date' => $date,
        ]);
    }

    public function create(): RedirectResponse
    {
        $order = $this->orders->create(Auth::user(), []);

        return $order
            ? redirect('/orders/' . $order->id . '/show')
            : redirect('/orders');
    }

    public function show(int $id, DiscountRepositoryContract $discountRepo): InertiaResponse|RedirectResponse
    {
        $order = $this->orders->findById($id, true);
        if (! $order) {
            flash('Order with id: ' . $id . ' could not be found', 'danger');

            return redirect('/orders');
        }

        $orderPayload = [
            'id' => $order->id,
            'total' => (float) $order->total,
            'complete' => (bool) $order->complete,
            'created_at' => $order->created_at?->toDateTimeString(),
            'customer' => $order->customer ? [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'phone' => $order->customer->phone,
                'points' => $order->customer->points,
            ] : null,
            'products' => $order->productInstances->map(fn ($p) => [
                'id' => $p->id,
                'pivot_id' => $p->pivot->id,
                'name' => $p->product->name ?? 'Product',
                'quantity' => $p->pivot->quantity,
                'price' => (float) $p->price,
            ])->values(),
            'liquids' => $order->liquidProducts->map(fn ($l) => [
                'id' => $l->id,
                'recipe_name' => $l->recipe->name ?? 'Custom',
                'size' => $l->size,
                'nicotine' => $l->nicotine,
                'price' => (float) ($l->price ?? 0),
            ])->values(),
            'discounts' => $order->discounts->map(fn ($d) => [
                'id' => $d->id,
                'pivot_id' => $d->pivot->id ?? null,
                'name' => $d->name,
                'applied' => (float) ($d->pivot->applied ?? 0),
            ])->values(),
            'payments' => $order->payments->map(fn ($p) => [
                'id' => $p->id,
                'amount' => (float) $p->amount,
                'type' => $p->type,
            ])->values(),
        ];

        if ($order->complete) {
            return Inertia::render('Orders/ShowClosed', [
                'order' => $orderPayload,
            ]);
        }

        $discounts = $discountRepo->getAll();

        $storeInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store);
        $flatInstances = collect($storeInstances)->map(fn ($i) => [
            'id' => $i->id,
            'label' => ($i->product->name ?? 'Product') . ' — $' . number_format($i->price, 2),
        ])->values()->all();

        $activeRecipes = $this->recipes->getAll(true);

        return Inertia::render('Orders/ShowOpen', [
            'order' => $orderPayload,
            'discounts' => collect($discounts)->map(fn ($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'type' => $d->type,
                'amount' => $d->amount,
                'approval' => (bool) $d->approval,
            ])->values(),
            'instances' => $flatInstances,
            'recipes' => collect($activeRecipes)->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
            ])->values(),
            'liquidOptions' => [
                'sizes' => collect(config('store.bottle_sizes'))->map(fn ($label, $value) => [
                    'title' => $label,
                    'value' => $value,
                ])->values(),
                'nicotine' => collect(config('store.nicotine_levels'))->map(fn ($label, $value) => [
                    'title' => $label,
                    'value' => $value,
                ])->values(),
                'menthol' => collect(config('store.menthol_levels'))->map(fn ($label, $value) => [
                    'title' => $label,
                    'value' => $value,
                ])->values(),
                'vg' => collect(config('store.vg_levels'))->map(fn ($label, $value) => [
                    'title' => $label,
                    'value' => $value,
                ])->values(),
            ],
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $this->orders->delete($id);

        return redirect('/orders/create');
    }

    public function addProduct(int $id, Request $request): RedirectResponse
    {
        $this->orders->addProductsToOrder($this->orders->findById($id), $request->all());

        return back();
    }

    public function quantityUpdate(int $id, Request $request): RedirectResponse
    {
        if ($request->query('pid') && $request->query('inc')) {
            $this->orders->updateProductQuantity($id, $request->query('pid'), $request->query('inc'));
        }

        return back();
    }

    public function removeProduct(int $id, int $pid): RedirectResponse
    {
        $this->orders->removeProductFromOrder($this->orders->findById($id), $pid);

        return back();
    }

    public function addLiquid(int $id, Request $request): RedirectResponse
    {
        $this->orders->addLiquidsToOrder($this->orders->findById($id), $request->all());

        return back();
    }

    public function removeLiquid(int $id, int $lid): RedirectResponse
    {
        $this->orders->removeLiquidFromOrder($this->orders->findById($id), $lid);

        return back();
    }

    public function duplicateLiquid(int $id): RedirectResponse
    {
        $this->orders->duplicateLiquid($id);

        return back();
    }

    public function lastLiquid(int $id, LiquidProductRepositoryContract $liquidRepo, RecipeRepositoryContract $recipeRepo): JsonResponse
    {
        $order = $this->orders->findById($id);
        if ($order->customer) {
            $liquid = $liquidRepo->findCustomersLastLiquid($order->customer->id, $recipeRepo);
            if ($liquid) {
                return response()->json($liquid);
            }
        }

        return response()->json('fail');
    }

    public function addDiscount(int $id, Request $request, DiscountRepositoryContract $discountRepo): RedirectResponse
    {
        $discount = $discountRepo->findById($request->get('discount'));
        if ($request->get('redeem') === 'true') {
            $this->orders->addRedeemedDiscount($this->orders->findById($id), $discount);

            return back();
        }
        if ($discount->approval) {
            if ($request->get('pin') === config('store.manager_pin')) {
                $this->orders->addDiscountToOrder($this->orders->findById($id), $discount);
            } else {
                flash('Sorry the manager pin was incorrect', 'danger');
            }

            return back();
        }

        $this->orders->addDiscountToOrder($this->orders->findById($id), $discount);

        return back();
    }

    public function removeDiscount(int $id, int $did): RedirectResponse
    {
        $this->orders->removeDiscountFromOrder($this->orders->findById($id), $did);

        return back();
    }

    public function customer(int $id, Request $request, CustomerRepositoryContract $customerRepo, DiscountRepositoryContract $discountRepo): RedirectResponse
    {
        $customer = $customerRepo->findByPhone($request->get('phone'));
        if (! $customer) {
            return back();
        }
        $this->orders->addCustomerToOrder($this->orders->findById($id), $customer, $discountRepo);

        return back();
    }

    public function addPayment(int $id, Request $request): RedirectResponse
    {
        $order = $this->orders->findById($id);
        if (count($order->liquidProducts) === 0 && count($order->productInstances) === 0) {
            flash('This order does not have any items, Add products or delete the order', 'danger');

            return back();
        }
        $change = $this->orders->addPaymentToOrder($order, $request->all());
        if ($order->calculator()->checkComplete()) {
            event(new OrderCompleted($order));

            return redirect("/orders/$order->id/receipt")->with('change', number_format($change, 2));
        }

        return back();
    }

    public function deletePayment(int $id): RedirectResponse
    {
        if (Auth::user()->hasRole('manager')) {
            $this->orders->deletePayment($id);
        } else {
            flash("You don't have permission to remove a payment please talk to a manager", 'danger');
        }

        return back();
    }

    public function receipt(int $id): InertiaResponse
    {
        $order = $this->orders->findById($id);

        return Inertia::render('Orders/Receipt', [
            'order' => [
                'id' => $order->id,
                'total' => (float) $order->total,
                'complete' => (bool) $order->complete,
                'created_at' => $order->created_at?->toDateTimeString(),
                'customer' => $order->customer?->name,
                'products' => $order->productInstances->map(fn ($p) => [
                    'name' => $p->product->name ?? 'Product',
                    'quantity' => $p->pivot->quantity,
                    'price' => (float) $p->price,
                ])->values(),
                'liquids' => $order->liquidProducts->map(fn ($l) => [
                    'recipe_name' => $l->recipe->name ?? 'Custom',
                    'price' => (float) ($l->price ?? 0),
                ])->values(),
                'payments' => $order->payments->map(fn ($p) => [
                    'amount' => (float) $p->amount,
                    'type' => $p->type,
                ])->values(),
            ],
            'change' => session('change'),
        ]);
    }

    public function emailReceipt(Request $request): JsonResponse
    {
        if ($request->get('order')) {
            $order = $this->orders->findById($request->get('order'));
            if ($order) {
                $this->dispatch(new SendReceiptEmail($order));

                return response()->json(['status' => 'success']);
            }
        }

        return response()->json(['status' => 'fail']);
    }
}
