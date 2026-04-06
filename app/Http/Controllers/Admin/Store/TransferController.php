<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Repositories\Store\Transfer\TransferRepositoryContract;
use App\Services\Store\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TransferController extends Controller
{
    public function __construct(protected TransferRepositoryContract $transfersRepo)
    {
    }

    public function index(): InertiaResponse
    {
        $paginator = $this->transfersRepo->getAll();
        $stores = config('store.stores', []);

        return Inertia::render('Admin/Store/Transfers/Index', [
            'transfers' => collect($paginator->items())->map(fn ($t) => [
                'id' => $t->id,
                'from_store' => $t->from_store,
                'from_store_name' => $stores[$t->from_store] ?? null,
                'to_store' => $t->to_store,
                'to_store_name' => $stores[$t->to_store] ?? null,
                'received' => (bool) $t->received,
                'created_at' => $t->created_at?->toDateTimeString(),
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

        return Inertia::render('Admin/Store/Transfers/Create', [
            'instances' => $flat,
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->transfersRepo->create($request->all());

        return redirect('/admin/store/transfers');
    }

    public function show(int $id): InertiaResponse
    {
        $transfer = $this->transfersRepo->findById($id);
        $stores = config('store.stores', []);

        return Inertia::render('Admin/Store/Transfers/Show', [
            'transfer' => [
                'id' => $transfer->id,
                'from_store' => $transfer->from_store,
                'from_store_name' => $stores[$transfer->from_store] ?? null,
                'to_store' => $transfer->to_store,
                'to_store_name' => $stores[$transfer->to_store] ?? null,
                'received' => (bool) $transfer->received,
                'created_at' => $transfer->created_at?->toDateTimeString(),
                'instances' => $transfer->productInstances->map(fn ($i) => [
                    'id' => $i->id,
                    'quantity' => $i->pivot->quantity,
                    'received' => (bool) ($i->pivot->received ?? false),
                ])->values(),
            ],
        ]);
    }

    public function receive(int $id, InventoryService $inventoryService): RedirectResponse
    {
        $transfer = $this->transfersRepo->findById($id);
        if ($inventoryService->adjustInventoryForTransfer($transfer)) {
            $this->transfersRepo->receiveTransfer($transfer);

            return redirect('/admin/store/transfers');
        }

        return back();
    }
}
