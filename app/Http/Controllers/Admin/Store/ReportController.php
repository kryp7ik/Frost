<?php

namespace App\Http\Controllers\Admin\Store;

use App\Services\Store\ReportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    /**
     * @var ReportService
     */
    protected $reportService;

    /**
     * ReportController constructor.
     *
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Generates sales report (minimal & detailed)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sales(Request $request)
    {
        $data = $this->reportService->generateSalesReport($request->get('store'), $request->get('start'), $request->get('end'), $request->get('type'));
        $filters = [
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'store' => $request->input('store'),
            'type' => $request->get('type')
        ];
        if ($filters['type'] == 'minimal') {
            return view('backend.store.report.sales.minimal', compact('data','filters'));
        } else {
            return view('backend.store.report.sales.detailed', compact('data','filters'));
        }
    }

    /**
     * Generates inventory report
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inventory(Request $request)
    {
        $store = $request->get('store');
        $data = $this->reportService->generateInventoryReport($store);
        return view('backend.store.report.inventory', compact('data','store'));
    }

}
