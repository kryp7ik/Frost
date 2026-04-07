<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Jobs\SendInventoryCountAlertEmail;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use App\Services\Store\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class InventoryController extends Controller
{
    public function __construct(protected ProductInstanceRepositoryContract $productInstances)
    {
    }

    public function create(): InertiaResponse
    {
        $sortedInstances = $this->productInstances->getActiveWhereStore(Auth::user()->store, true);

        $groups = [];
        foreach (($sortedInstances ?: []) as $categoryName => $instances) {
            $groupInstances = [];
            foreach ($instances as $instance) {
                $groupInstances[] = [
                    'id' => $instance['instance_id'],
                    'label' => $instance['name'],
                    'current_stock' => $instance['stock'] ?? null,
                ];
            }
            $groups[] = [
                'category' => $categoryName,
                'instances' => $groupInstances,
            ];
        }

        return Inertia::render('Admin/Store/Inventory/Create', [
            'groups' => $groups,
        ]);
    }

    public function process(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $alertStack = $inventoryService->processInventoryCount($request->all());
        if (count($alertStack['products']) > 0) {
            $this->dispatch(new SendInventoryCountAlertEmail($alertStack));
        }

        return redirect('/');
    }
}
