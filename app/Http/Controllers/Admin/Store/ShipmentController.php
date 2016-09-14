<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Shipment\ShipmentRepositoryContract;
use App\Services\Store\InventoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{

    /**
     * @var ShipmentRepositoryContract
     */
    protected $shipmentsRepo;

    public function __construct(ShipmentRepositoryContract $shipmentsRepo)
    {
        $this->shipmentsRepo = $shipmentsRepo;
    }

    public function index()
    {
        $shipments = $this->shipmentsRepo->getAll();
        return view('backend.store.shipments.index', compact('shipments'));
    }

    public function create(ProductInstanceRepositoryContract $instancesRepo)
    {
        $sortedInstances = $instancesRepo->getActiveWhereStore(Auth::user()->store, true);
        return view('backend.store.shipments.create', compact('sortedInstances'));
    }

    public function store(Request $request, InventoryService $inventoryService)
    {
        $shipment = $this->shipmentsRepo->create($request->all());
        $inventoryService->adjustInventoryForShipment($shipment);
        return redirect('/admin/store/shipments');
    }

    public function show($id)
    {
        $shipment = $this->shipmentsRepo->findById($id);
        return view('backend.store.shipments.show', compact('shipment'));
    }

}