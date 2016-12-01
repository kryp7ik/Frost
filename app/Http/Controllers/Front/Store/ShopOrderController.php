<?php

namespace App\Http\Controllers\Front\Store;

use App\Events\OrderCompleted;
use App\Jobs\SendReceiptEmail;
use App\Repositories\Store\Customer\CustomerRepositoryContract;
use App\Repositories\Store\Discount\DiscountRepositoryContract;
use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;
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
     * @param DiscountRepositoryContract $discountRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($id, DiscountRepositoryContract $discountRepo)
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
            $sortedDiscounts = $discountRepo->getAll(true);
            $redeemableDiscounts = ($order->customer) ? $discountRepo->getRedeemable($order->customer->points) : array();
            return view('orders.show.open', compact('order', 'recipes', 'sortedInstances', 'sortedDiscounts', 'redeemableDiscounts'));
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $this->orders->delete($id);
        return redirect('/orders/create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProduct($id, Request $request)
    {
        $this->orders->addProductsToOrder($this->orders->findById($id), $request->all());
        return back();
    }

    public function quantityUpdate($id, Request $request)
    {
        if($request->query('pid') && $request->query('inc')) {
            $this->orders->updateProductQuantity($id, $request->query('pid'), $request->query('inc'));
        }
        return back();
    }

    /**
     * @param int $id ShopOrder id
     * @param int $pid the id of the row in the pivot table
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeProduct($id, $pid)
    {
        $this->orders->removeProductFromOrder($this->orders->findById($id), $pid);
        return back();
    }

    /**
     * @param int $id ShopOrder id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLiquid($id, Request $request)
    {
        $this->orders->addLiquidsToOrder($this->orders->findById($id), $request->all());
        return back();
    }

    /**
     * @param int $id the ShopOrder id
     * @param int $lid the LiquidProduct id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeLiquid($id, $lid)
    {
        $this->orders->removeLiquidFromOrder($this->orders->findById($id), $lid);
        return back();
    }

    /**
     * Finds the last LiquidProduct that the customer who belongs to the specified ShopOrder ordered
     * @param int $id ShopOrder id
     * @param LiquidProductRepositoryContract $liquidRepo
     * @param RecipeRepositoryContract $recipeRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function lastLiquid($id, LiquidProductRepositoryContract $liquidRepo, RecipeRepositoryContract $recipeRepo)
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

    /**
     * @param int $id
     * @param Request $request
     * @param DiscountRepositoryContract $discountRepo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDiscount($id, Request $request, DiscountRepositoryContract $discountRepo)
    {
        $discount = $discountRepo->findById($request->get('discount'));
        if ($request->get('redeem') == 'true') {
            $this->orders->addRedeemedDiscount($this->orders->findById($id), $discount);
            return back();
        }
        if ($discount->approval) {
            if ($request->get('pin') == config('store.manager_pin')) {
                $this->orders->addDiscountToOrder($this->orders->findById($id), $discount);
            } else {
                flash('Sorry the manager pin was incorrect', 'danger');
            }
            return back();
        } else {
            $this->orders->addDiscountToOrder($this->orders->findById($id), $discount);
            return back();
        }
    }

    /**
     * @param int $id
     * @param int $did
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeDiscount($id, $did)
    {
        $this->orders->removeDiscountFromOrder($this->orders->findById($id), $did);
        return back();
    }

    /**
     * Adds or changes the customer for the given order
     * @param int $id
     * @param Request $request
     * @param CustomerRepositoryContract $customerRepo
     * @param DiscountRepositoryContract $discountRepo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function customer($id, Request $request, CustomerRepositoryContract $customerRepo, DiscountRepositoryContract $discountRepo)
    {
        $customer = $customerRepo->findByPhone($request->get('phone'));
        if (!$customer) return back();
        $this->orders->addCustomerToOrder($this->orders->findById($id), $customer, $discountRepo);
        return back();
    }

    /**
     * Adds a payment to the order and checks if the order has been paid in full
     * If the order does not contain any products or liquids return back with warning
     * If the order is complete: fire event & redirect to the receipt view with the change due stored at $_SESSION['change']
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addPayment($id, Request $request)
    {
        $order = $this->orders->findById($id);
        if (count($order->liquidProducts) == 0 && count($order->productInstances) == 0){
            flash('This order does not have any items, Add products or delete the order', 'danger');
            return back();
        }
        $change = $this->orders->addPaymentToOrder($order, $request->all());
        if($order->calculator()->checkComplete()) {
            event(new OrderCompleted($order));
            return redirect("/orders/$order->id/receipt")->with('change', number_format($change, 2));
        } else {
            return back();
        }
    }

    /**
     * This action can only be accessed by users with the 'manager' role
     * Deletes a payment applied to an order and sets the order to incomplete
     * Returns user the the order.open view to complete the order or delete entirely
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePayment($id)
    {
        if (Auth::user()->hasRole('manager')) {
            $this->orders->deletePayment($id);
        } else flash('You don\'t have permission to remove a payment please talk to a manager', 'danger');
        return back();
    }

    /**
     * Displays the order summary and receipt options after an order has been completed
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function receipt($id)
    {
        $order = $this->orders->findById($id);
        return view('orders.receipt', compact('order'));
    }

    /**
     * Called via AJAX
     * Accepts post request containing an 'order' attribute where the value is the order id
     * Fires the SendReceiptEmail Event which e-mails a receipt to the customer for the specified order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailReceipt(Request $request)
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
