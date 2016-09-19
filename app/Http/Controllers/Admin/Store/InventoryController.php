<?php

namespace App\Http\Controllers\Admin\Store;

use App\Jobs\SendInventoryCountAlertEmail;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Services\Store\InventoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{

    /**
     * @var ProductInstanceRepositoryContract
     */
    protected $productInstances;

    /**
     * ProductController constructor.
     * @param ProductInstanceRepositoryContract $instances
     */
    public function __construct(ProductInstanceRepositoryContract $instances)
    {
        $this->productInstances = $instances;
    }

    /**
     * Inventory Count view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);
        return view('backend.store.inventory.create', compact('sortedInstances'));
    }

    /**
     * Processes an inventory account by updating the inventory and sending notification email to admin(s)
     * @param Request $request
     * @param InventoryService $inventoryService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function process(Request $request, InventoryService $inventoryService)
    {
        $alertStack = $inventoryService->processInventoryCount($request->all());
        if (count($alertStack['products']) > 0) $this->dispatch(new SendInventoryCountAlertEmail($alertStack));
        return redirect('/admin');
    }
}