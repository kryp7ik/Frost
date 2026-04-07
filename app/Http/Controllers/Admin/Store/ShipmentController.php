<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Shipment\ShipmentRepositoryContract;
use App\Services\Store\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ShipmentController extends Controller
{
    public function __construct(protected ShipmentRepositoryContract $shipmentsRepo)
    {
    }

    public function index(): InertiaResponse
    {
        $paginator = $this->shipmentsRepo->getAll();
        $stores = config('store.stores', []);

        return Inertia::render('Admin/Store/Shipments/Index', [
            'shipments' => collect($paginator->items())->map(fn ($s) => [
                'id' => $s->id,
                'store' => $s->store,
                'store_name' => $stores[$s->store] ?? null,
                'created_at' => $s->created_at?->toDateTimeString(),
                'instance_count' => $s->productInstances->count(),
            ])->values(),
        ]);
    }

    public function create(ProductInstanceRepositoryContract $instancesRepo): InertiaResponse
    {
        $sortedInstances = $instancesRepo->getActiveWhereStore(Auth::user()->store, true);

        $flat = [];
        foreach (($sortedInstances ?: []) as $categoryName => $instances) {
            foreach ($instances as $instance) {
                $flat[] = [
                    'id' => $instance['instance_id'],
                    'category' => $categoryName,
                    'label' => $instance['name'] . ' (stock: ' . ($instance['stock'] ?? 0) . ')',
                ];
            }
        }

        return Inertia::render('Admin/Store/Shipments/Create', [
            'instances' => $flat,
        ]);
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $shipment = $this->shipmentsRepo->create($request->all());
        $inventoryService->adjustInventoryForShipment($shipment);

        return redirect('/admin/store/shipments');
    }

    public function show(int $id): InertiaResponse
    {
        $shipment = $this->shipmentsRepo->findById($id);
        $stores = config('store.stores', []);

        return Inertia::render('Admin/Store/Shipments/Show', [
            'shipment' => [
                'id' => $shipment->id,
                'store' => $shipment->store,
                'store_name' => $stores[$shipment->store] ?? null,
                'created_at' => $shipment->created_at?->toDateTimeString(),
                'instances' => $shipment->productInstances->map(fn ($i) => [
                    'id' => $i->id,
                    'quantity' => $i->pivot->quantity,
                ])->values(),
            ],
        ]);
    }
}
