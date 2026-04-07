<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Services\Store\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService)
    {
    }

    public function sales(Request $request): InertiaResponse
    {
        $data = $this->reportService->generateSalesReport(
            $request->get('store'),
            $request->get('start'),
            $request->get('end'),
            $request->get('type')
        );

        return Inertia::render('Admin/Store/Report/Sales', [
            'data' => $data,
            'filters' => [
                'start' => $request->input('start'),
                'end' => $request->input('end'),
                'store' => $request->input('store'),
                'type' => $request->get('type', 'detailed'),
            ],
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }

    public function inventory(Request $request): InertiaResponse
    {
        $store = $request->get('store');
        $data = $this->reportService->generateInventoryReport($store);

        return Inertia::render('Admin/Store/Report/Inventory', [
            'data' => $data,
            'store' => $store,
            'stores' => collect(config('store.stores'))->map(fn ($name, $id) => [
                'id' => (int) $id,
                'name' => $name,
            ])->values(),
        ]);
    }
}
