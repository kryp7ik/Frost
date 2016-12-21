<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Transfer\TransferRepositoryContract;
use App\Services\Store\InventoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{

    /**
     * @var TransferRepositoryContract
     */
    protected $transfersRepo;

    public function __construct(TransferRepositoryContract $transfersRepo)
    {
        $this->transfersRepo = $transfersRepo;
    }

    /**
     * List of all Transfers
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $transfers = $this->transfersRepo->getAll();
        return view('backend.store.transfers.index', compact('transfers'));
    }

    /**
     * Displays the view to create a new Transfer
     * @param ProductInstanceRepositoryContract $instancesRepo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ProductInstanceRepositoryContract $instancesRepo)
    {
        $sortedInstances = $instancesRepo->getActiveWhereStore(Auth::user()->store, true);
        return view('backend.store.transfers.create', compact('sortedInstances'));
    }

    /**
     * Saves a Transfer to the database
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->transfersRepo->create($request->all());
        return redirect('/admin/store/transfers');
    }

    /**
     * Displays a Transfer
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $transfer = $this->transfersRepo->findById($id);
        return view('backend.store.transfers.show', compact('transfer'));
    }

    /**
     * Called when the recipient of the Transfer receives it and adjusts the inventory accordingly.
     * @param int $id
     * @param InventoryService $inventoryService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function receive($id, InventoryService $inventoryService)
    {
        $transfer = $this->transfersRepo->findById($id);
        if ($inventoryService->adjustInventoryForTransfer($transfer)) {
            $this->transfersRepo->receiveTransfer($transfer);
            return redirect('/admin/store/transfers');
        }
        return back();
    }

}